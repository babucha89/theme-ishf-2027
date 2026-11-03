// -----------------------------------------------------------------------------
//  Block: BS EXAMPLE (Editor)
//  Edited: 3L, 23-JUL-2026
// -----------------------------------------------------------------------------
//  TODO: Rename the block slug from "bs-example" to "bs-yourblock".
//  This block is a template — duplicate the folder and customise as needed.
// -----------------------------------------------------------------------------

wp.blocks.registerBlockType('uikit-boostsolutions/bs-example', {
	title: 'BS Example',
	description: 'Boost Solutions: Template block — duplicate and customise for new blocks.',
	icon: 'layout',
	category: 'boost-solutions',
	attributes: {
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
		isFeatured: {
			type: 'boolean',
			default: false
		},
	},
	edit: EditComponent,
	save: function () { return null; }
});


// -----------------------------------------------------------------------------
//  Edit Component (Gutenberg Editor)
// -----------------------------------------------------------------------------
function EditComponent(props) {
	var el = wp.element.createElement;
	var attributes = props.attributes;
	var setAttributes = props.setAttributes;

	// -- Destructure commonly used WP components
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var ToggleControl = wp.components.ToggleControl;
	var Button = wp.components.Button;
	var MediaUpload = wp.blockEditor.MediaUpload;


	// -- Update handlers
	function updateTitle(newValue) {
		setAttributes({ title: newValue });
	}

	function updateDescription(newValue) {
		setAttributes({ description: newValue });
	}

	function updateIsFeatured(newValue) {
		setAttributes({ isFeatured: newValue });
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
	return el('div', { className: 'bs-example-editor' }, [

		// -- Block Title
		el('h4', { key: 'block-title' }, 'BS Example'),

		// -- TextControl: Single-line text field
		el(TextControl, {
			key: 'title',
			label: 'Title',
			value: attributes.title,
			onChange: updateTitle,
		}),

		// -- TextareaControl: Multi-line text field
		el(TextareaControl, {
			key: 'description',
			label: 'Description',
			value: attributes.description,
			onChange: updateDescription,
		}),

		// -- MediaUpload: Image picker
		el('div', { key: 'image-upload', className: 'bs-example-editor__media' }, [
			el('label', { key: 'image-label', className: 'components-base-control__label' }, 'Image'),
			attributes.imageUrl
				? el('div', { key: 'image-preview', className: 'bs-example-editor__preview' }, [
					el('img', {
						key: 'preview-img',
						src: attributes.imageUrl,
						alt: attributes.imageAlt,
						style: { maxWidth: '100%', height: 'auto', display: 'block', marginBottom: '8px' }
					}),
					el(Button, {
						key: 'remove-btn',
						onClick: onRemoveImage,
						isDestructive: true,
						variant: 'secondary',
						isSmall: true,
					}, 'Remove Image'),
				])
				: el(MediaUpload, {
					key: 'upload',
					onSelect: onSelectImage,
					allowedTypes: ['image'],
					render: function (obj) {
						return el(Button, {
							onClick: obj.open,
							variant: 'secondary',
						}, 'Select Image');
					}
				}),
		]),

		// -- ToggleControl: Boolean switch
		el(ToggleControl, {
			key: 'is-featured',
			label: 'Featured',
			help: attributes.isFeatured ? 'This block is marked as featured.' : 'Toggle to mark as featured.',
			checked: attributes.isFeatured,
			onChange: updateIsFeatured,
		}),
	]);
}
