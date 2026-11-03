// -----------------------------------------------------------------------------
//  Block: ISHF GALLERY (Editor)
//  Edited: 3L, 30-JUL-2026
// -----------------------------------------------------------------------------
wp.blocks.registerBlockType('ishf/gallery', {
	title: 'ISHF Gallery',
	description: 'ISHF: Insert a UIkit slideshow/gallery.',
	icon: 'format-gallery',
	category: 'ishf',
	attributes: {
		blockId: {
			type: 'string',
			default: ''
		},
		images: {
			type: 'array',
			default: []
		}
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
	var images = attributes.images || [];
	// -- Auto-generate blockId on mount
	useEffect(function() {
		if (!attributes.blockId) {
			setAttributes({
				blockId: 'gallery-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6)
			});
		}
	}, []);
	// -- Destructure commonly used WP components
	var Button = wp.components.Button;
	var MediaUpload = wp.blockEditor.MediaUpload;
	// -- Handlers
	function onSelectImages(media) {
		var updated = media.map(function(img) {
			return {
				id: img.id,
				url: img.url,
				alt: img.alt || '',
				caption: img.caption || ''
			};
		});
		setAttributes({
			images: updated
		});
	}

	function removeImage(index) {
		var updated = images.slice();
		updated.splice(index, 1);
		setAttributes({
			images: updated
		});
	}

	function moveImage(from, to) {
		if (to < 0 || to >= images.length) return;
		var updated = images.slice();
		var temp = updated[from];
		updated[from] = updated[to];
		updated[to] = temp;
		setAttributes({
			images: updated
		});
	}
	// -- Render
	return el('div', {
		className: 'ishf-gallery-editor',
		style: {
			padding: '20px',
			border: '1px solid #ccc',
			borderRadius: '4px',
			background: '#fafafa'
		}
	}, [
		// -- Block Header
		el('div', {
			key: 'header',
			style: {
				display: 'flex',
				alignItems: 'center',
				marginBottom: '20px',
				gap: '10px'
			}
		}, [
			el('span', {
				key: 'icon',
				className: 'dashicons dashicons-format-gallery',
				style: {
					color: '#007cba'
				}
			}),
			el('h4', {
				key: 'title',
				style: {
					margin: 0,
					color: '#007cba'
				}
			}, 'ISHF Gallery')
		]),
		// -- Image Grid (preview of selected images)
		images.length > 0 && el('div', {
			key: 'image-grid',
			style: {
				display: 'grid',
				gridTemplateColumns: 'repeat(auto-fill, minmax(140px, 1fr))',
				gap: '10px',
				marginBottom: '20px'
			}
		}, images.map(function(img, index) {
			return el('div', {
				key: 'img-' + index,
				style: {
					position: 'relative',
					border: '1px solid #ddd',
					borderRadius: '4px',
					background: '#fff',
					overflow: 'hidden',
					display: 'flex',
					flexDirection: 'column',
					justifyContent: 'space-between'
				}
			}, [
				el('img', {
					key: 'img',
					src: img.url,
					alt: img.alt,
					style: {
						width: '100%',
						height: '100px',
						objectFit: 'cover',
						display: 'block'
					}
				}),
				el('div', {
					key: 'controls',
					style: {
						display: 'flex',
						justifyContent: 'space-between',
						padding: '4px',
						background: '#f8f9f9',
						borderTop: '1px solid #ddd'
					}
				}, [
					el('div', {
						key: 'move-buttons'
					}, [
						el(Button, {
							key: 'left',
							icon: 'arrow-left-alt2',
							isSmall: true,
							disabled: index === 0,
							onClick: function() {
								moveImage(index, index - 1);
							}
						}),
						el(Button, {
							key: 'right',
							icon: 'arrow-right-alt2',
							isSmall: true,
							disabled: index === images.length - 1,
							onClick: function() {
								moveImage(index, index + 1);
							},
							style: {
								background: 'white',
								borderRadius: '50%'
							}
						})
					]),
					// -- Image number badge
					el('span', {
						style: {
							position: 'absolute',
							top: '4px',
							left: '4px',
							background: '#007cba',
							color: 'white',
							borderRadius: '50%',
							width: '22px',
							height: '22px',
							display: 'flex',
							alignItems: 'center',
							justifyContent: 'center',
							fontSize: '11px',
							fontWeight: 'bold'
						}
					}, (index + 1).toString())
				])
			]);
		})),
		el(MediaUpload, {
			key: 'upload',
			onSelect: onSelectImages,
			allowedTypes: ['image'],
			multiple: true,
			gallery: true,
			value: images.map(function(img) {
				return img.id;
			}),
			render: function(obj) {
				return el('div', {
					style: {
						border: '2px dashed #ccc',
						padding: '40px 20px',
						textAlign: 'center',
						background: '#f8f9f9',
						borderRadius: '4px',
						cursor: 'pointer'
					},
					onClick: obj.open
				}, [
					el('div', {
						style: {
							marginBottom: '15px',
							color: '#646970'
						}
					}, images.length > 0 ? images.length + ' image(s) selected. Click to add or replace.' : 'Click to select images for the gallery slideshow.'),
					el(Button, {
						variant: 'secondary',
						onClick: obj.open
					}, images.length > 0 ? 'Manage Gallery' : 'Select Images')
				]);
			}
		})
	]);
}
