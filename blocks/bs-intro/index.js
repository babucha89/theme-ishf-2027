// -----------------------------------------------------------------------------
//  Block: BS INTRO (Editor)
//  Edited: 3L, 23-JUL-2026
// -----------------------------------------------------------------------------
wp.blocks.registerBlockType('uikit-boostsolutions/bs-intro', {
	title: 'BS Intro',
	description: 'Boost Solutions: Intro block.',
	icon: 'layout',
	category: 'boost-solutions',
	attributes: {
		blockId: {
			type: 'string',
			default: ''
		},
		layout: {
			type: 'string',
			default: 'no-image'
		},
		title: {
			type: 'string',
			default: ''
		},
		description: {
			type: 'string',
			default: ''
		},
		imageUrl: {
			type: 'string',
			default: ''
		},
		imageAlt: {
			type: 'string',
			default: ''
		},
	},
	edit: EditComponent,
	save: function() {
		return null;
	}
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
			setAttributes({ blockId: 'intro-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6) });
		}
	}, []);
	// -- Destructure commonly used WP components
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var Button = wp.components.Button;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var RichText = wp.blockEditor.RichText;
	// -- Update handlers
	function updateTitle(newValue) {
		setAttributes({
			title: newValue
		});
	}

	function updateDescription(newValue) {
		setAttributes({
			description: newValue
		});
	}

	function updateLayout(newValue) {
		setAttributes({
			layout: newValue
		});
	}

	function onSelectImage(media) {
		setAttributes({
			imageUrl: media.url,
			imageAlt: media.alt || ''
		});
	}

	function onRemoveImage() {
		setAttributes({
			imageUrl: '',
			imageAlt: ''
		});
	}
	// -- Render
	return el('div', {
		className: 'bs-example-editor'
	}, [
		// -- Block Title
		el('h4', {
			key: 'block-title'
		}, 'BS Intro'),
		// -- Layout Selector
		el(SelectControl, {
			key: 'layout',
			label: 'Layout',
			value: attributes.layout,
			options: [{
				label: 'Image on Left',
				value: 'image-left'
			}, {
				label: 'Image on Right',
				value: 'image-right'
			}, {
				label: 'No Image',
				value: 'no-image'
			}],
			onChange: updateLayout,
		}),
		// -- MediaUpload: Image picker
		attributes.layout !== 'no-image' && el('div', {
			key: 'image-upload',
			className: 'bs-example-editor__media',
			style: {
				marginBottom: '20px'
			}
		}, [
			el('label', {
				key: 'image-label',
				className: 'components-base-control__label'
			}, 'Image'),
			el(MediaUpload, {
				key: 'upload',
				onSelect: onSelectImage,
				allowedTypes: ['image'],
				value: attributes.imageUrl,
				render: function(obj) {
					if (attributes.imageUrl) {
						// State: Image selected
						return el('div', {
							style: {
								position: 'relative',
								border: '1px solid #ccc',
								borderRadius: '4px',
								padding: '4px',
								display: 'inline-block',
								width: '100%'
							}
						}, [
							el('img', {
								src: attributes.imageUrl,
								alt: attributes.imageAlt,
								style: {
									maxWidth: '100%',
									height: 'auto',
									display: 'block',
									borderRadius: '2px'
								}
							}),
							el('div', {
								style: {
									position: 'absolute',
									top: '12px',
									right: '12px'
								}
							}, el(Button, {
								onClick: onRemoveImage,
								isDestructive: true,
								variant: 'primary',
								icon: 'trash',
								label: 'Remove Image'
							}))
						]);
					} else {
						// State: No image
						return el('div', {
							style: {
								border: '2px dashed #ccc',
								padding: '40px 20px',
								textAlign: 'center',
								background: '#f8f9f9',
								borderRadius: '4px',
								cursor: 'pointer',
								transition: 'all 0.2s ease'
							},
							onClick: obj.open
						}, [
							el('div', {
								style: {
									marginBottom: '15px',
									color: '#646970'
								}
							}, 'Drag and drop an image here, or click to upload.'),
							el(Button, {
								variant: 'secondary',
								onClick: obj.open,
							}, 'Select Image')
						]);
					}
				}
			})
		]),
		// -- TextControl: Single-line text field
		el(TextControl, {
			key: 'title',
			label: 'Title',
			value: attributes.title,
			onChange: updateTitle,
		}),
		// -- RichText: Multi-line rich text field for description
		el('div', {
			key: 'desc-wrapper',
			style: {
				marginBottom: '20px'
			}
		}, [
			el('label', {
				className: 'components-base-control__label'
			}, 'Description'),
			el('div', {
				style: {
					border: '1px solid #ccc',
					padding: '10px'
				}
			}, el(RichText, {
				key: 'description',
				tagName: 'div',
				value: attributes.description,
				onChange: updateDescription,
				placeholder: 'Add description here...',
			}))
		]),
	]);
}
