// -----------------------------------------------------------------------------
//  Block: ISHF VIDEO (Editor)
//  Edited: 3L, 30-JUL-2026
// -----------------------------------------------------------------------------

wp.blocks.registerBlockType('ishf/video', {
	title: 'ISHF Video',
	description: 'ISHF: Insert a YouTube or Brightcove video.',
	icon: 'video-alt3',
	category: 'ishf',
	attributes: {
		blockId: { type: 'string', default: '' },
		platform: { type: 'string', default: 'youtube' },
		videoId: { type: 'string', default: '' },
		isShorts: { type: 'boolean', default: false },
		playlistId: { type: 'string', default: '' },
		title: { type: 'string', default: '' },
		titleSize: { type: 'string', default: '2' },
		titlePosition: { type: 'string', default: 'none' },
		noMargin: { type: 'boolean', default: false }
	},
	edit: EditComponent,
	save: function() { return null; }
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
			setAttributes({ blockId: 'video-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6) });
		}
	}, []);

	// -- Destructure commonly used WP components
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var ToggleControl = wp.components.ToggleControl;

	// -- Update handlers
	function updatePlatform(val) { setAttributes({ platform: val }); }
	function updateVideoId(val) { setAttributes({ videoId: val }); }
	function updateIsShorts(val) { setAttributes({ isShorts: val }); }
	function updatePlaylistId(val) { setAttributes({ playlistId: val }); }
	function updateTitle(val) { setAttributes({ title: val }); }
	function updateTitleSize(val) { setAttributes({ titleSize: val }); }
	function updateTitlePosition(val) { setAttributes({ titlePosition: val }); }
	function updateNoMargin(val) { setAttributes({ noMargin: val }); }

	// -- Render
	return el('div', {
		className: 'ishf-video-editor',
		style: { padding: '16px', border: '1px solid #ddd', borderRadius: '4px', background: '#fff' }
	}, [
		// -- Block Title
		el('h4', { key: 'block-title', style: { marginTop: 0 } }, 'ISHF Video'),

		// -- Platform Selector
		el(SelectControl, {
			key: 'platform',
			label: 'Video Platform',
			value: attributes.platform,
			options: [
				{ label: 'YouTube', value: 'youtube' },
				{ label: 'Brightcove', value: 'brightcove' }
			],
			onChange: updatePlatform
		}),

		// -- Video ID
		el(TextControl, {
			key: 'videoId',
			label: attributes.platform === 'youtube' ? 'YouTube Video ID' : 'Brightcove Video ID',
			help: 'Example: dQw4w9WgXcQ',
			value: attributes.videoId,
			onChange: updateVideoId
		}),

		// -- YouTube Specific Options
		attributes.platform === 'youtube' && el('div', { key: 'yt-options', style: { padding: '10px', background: '#f5f5f5', marginBottom: '20px' } }, [
			el(ToggleControl, {
				key: 'isShorts',
				label: 'Is YouTube Short (Vertical Video)?',
				checked: attributes.isShorts,
				onChange: updateIsShorts
			}),
			el(TextControl, {
				key: 'playlistId',
				label: 'Playlist ID (Optional)',
				value: attributes.playlistId,
				onChange: updatePlaylistId
			})
		]),

		// -- Title
		el(TextControl, {
			key: 'title',
			label: 'Title (Optional)',
			help: 'Use {br} for a line break',
			value: attributes.title,
			onChange: updateTitle
		}),

		// -- Title Settings
		attributes.title && el('div', { key: 'title-settings', style: { display: 'flex', gap: '10px' } }, [
			el('div', { style: { flex: 1 } }, 
				el(SelectControl, {
					label: 'Title Size',
					value: attributes.titleSize,
					options: [
						{ label: 'H1', value: '1' },
						{ label: 'H2', value: '2' },
						{ label: 'H3', value: '3' },
						{ label: 'H4', value: '4' },
						{ label: 'H5', value: '5' },
						{ label: 'H6', value: '6' }
					],
					onChange: updateTitleSize
				})
			),
			el('div', { style: { flex: 1 } }, 
				el(SelectControl, {
					label: 'Title Position',
					value: attributes.titlePosition,
					options: [
						{ label: 'None', value: 'none' },
						{ label: 'Top', value: 'top' },
						{ label: 'Bottom', value: 'bottom' }
					],
					onChange: updateTitlePosition
				})
			)
		]),

		// -- Margin Toggle
		el(ToggleControl, {
			key: 'noMargin',
			label: 'Remove Margins?',
			checked: attributes.noMargin,
			onChange: updateNoMargin
		}),
	]);
}

