// -----------------------------------------------------------------------------
//  Block: ISHF VIDEO SLIDER (Editor)
//  Edited: 3L, 30-JUL-2026
// -----------------------------------------------------------------------------

wp.blocks.registerBlockType('ishf/video-slider', {
	title: 'ISHF Video Slider',
	description: 'ISHF: A slider containing multiple ISHF Video blocks.',
	icon: 'slides',
	category: 'ishf',
	attributes: {
		blockId: {
			type: 'string',
			default: ''
		},
		title: {
			type: 'string',
			default: 'Videoclips'
		}
	},
	edit: EditComponent,
	save: function () { return wp.element.createElement(wp.blockEditor.InnerBlocks.Content); }
});


// -----------------------------------------------------------------------------
//  Edit Component (Gutenberg Editor)
// -----------------------------------------------------------------------------
function EditComponent(props) {
	var el = wp.element.createElement;
	var useEffect = wp.element.useEffect;
	var attributes = props.attributes;
	var setAttributes = props.setAttributes;

	// -- Auto-generate blockId on mount
	useEffect(function() {
		if (!attributes.blockId) {
			setAttributes({ blockId: 'vslider-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6) });
		}
	}, []);

	// -- Destructure commonly used WP components
	var TextControl = wp.components.TextControl;
	var InnerBlocks = wp.blockEditor.InnerBlocks;

	var ALLOWED_BLOCKS = ['ishf/video'];
	
	var TEMPLATE = [
		['ishf/video', {}],
		['ishf/video', {}]
	];

	// -- Update handlers
	function updateTitle(newValue) {
		setAttributes({ title: newValue });
	}

	// -- Render
	return el('div', {
		className: 'ishf-video-slider-editor',
		style: { padding: '20px', border: '1px solid #ccc', borderRadius: '4px', background: '#fafafa' }
	}, [
		// -- Block Header
		el('div', { key: 'header', style: { display: 'flex', alignItems: 'center', marginBottom: '20px', gap: '10px' } }, [
			el('span', { key: 'icon', className: 'dashicons dashicons-video-alt3', style: { color: '#007cba' } }),
			el('h4', { key: 'title', style: { margin: 0, color: '#007cba' } }, 'ISHF Video Slider')
		]),

		// -- Section Title
		el(TextControl, {
			key: 'section-title',
			label: 'Section Title',
			value: attributes.title,
			onChange: updateTitle,
		}),

		// -- InnerBlocks Wrapper
		el('div', {
			key: 'inner-blocks',
			style: {
				marginTop: '20px',
				padding: '20px',
				border: '2px dashed #ddd',
				borderRadius: '4px',
				background: '#fff'
			}
		}, [
			el('p', { key: 'desc', style: { marginTop: 0, color: '#666', fontSize: '13px', fontWeight: 'bold' } }, 'Slide Items (Only ISHF Video blocks allowed here):'),
			el(InnerBlocks, {
				key: 'blocks',
				allowedBlocks: ALLOWED_BLOCKS,
				template: TEMPLATE,
				renderAppender: InnerBlocks.ButtonBlockAppender
			})
		])
	]);
}

