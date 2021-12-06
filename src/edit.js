/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import { Button, PanelBody, TextControl } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes, clientId }) {
	const [tenantUrl, setTenantUrl] = React.useState(attributes.tenant_url);
	const [mapId, setMapId] = React.useState(attributes.map_id);

	React.useEffect(() => {
		loadMap();
	}, []);

	const domId = 'mv_' + clientId;
	const loadMap = () => {

		// Clear map
		document.getElementById(domId).innerHTML = '';
		geoapps.RemoveMap(domId);

		// Initialize new map
		geoapps.Initialize(tenantUrl);
		var map = geoapps.AddMap(domId, mapId);
		if (map) {
			map.Controls.AddZoomControls();
		}
	};

	const onChangeTenantUrl = (tenantUrl) => {
		setTenantUrl(tenantUrl);
	};
	const onChangeMapId = (mapId) => {
		setMapId(mapId);
	};
	const onChangeWidth = (width) => {
		setAttributes({ width: width });
	};
	const onChangeHeight = (height) => {
		setAttributes({ height: height });
	};
	const onConnect = () => {
		setAttributes({ tenant_url: tenantUrl });
		setAttributes({ map_id: mapId });

		loadMap();
	};

	return (
		<div {...useBlockProps()}>
			<InspectorControls key="settings">
				<PanelBody title={'Settings'}>
					<TextControl
						value={tenantUrl}
						label="Tenant Url"
						onChange={onChangeTenantUrl}
					/>
					<TextControl
						value={mapId}
						label="Map Id"
						onChange={onChangeMapId}
					/>
					<Button onClick={onConnect} isPrimary={true}>
						Connect
					</Button>
				</PanelBody>
				<PanelBody title={'Dimensions'}>
					<TextControl
						value={attributes.width}
						label="Width"
						onChange={onChangeWidth}
					/>
					<TextControl
						value={attributes.height}
						label="Height"
						onChange={onChangeHeight}
					/>
				</PanelBody>
			</InspectorControls>
			<div
				style={{
					width: attributes.width,
					height: attributes.height,
					border: '1px solid black',
				}}
				id={domId}
			></div>
		</div>
	);
}
