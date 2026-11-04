// -----------------------------------------------------------------------------
//  Block: ISHF SEMINAR PROGRAM (Editor)
//  Edited: 3L, 23-JUL-2026
// -----------------------------------------------------------------------------
//  Features:
//  - Repeatable session rows (add / remove / reorder)
//  - Each session: time input + keynote title (side by side) + speakers
//  - Speakers autocomplete from CPT attendants (or free-text entry)
//  - Drag-and-drop reorder via move up/down buttons
//  - Render mode: Full / Split
// -----------------------------------------------------------------------------
(function() {
	var el = wp.element.createElement;
	var useState = wp.element.useState;
	var useEffect = wp.element.useEffect;
	var useRef = wp.element.useRef;
	var Fragment = wp.element.Fragment;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var Button = wp.components.Button;
	var Icon = wp.components.Icon;
	// =========================================================================
	//  SPEAKER AUTOCOMPLETE COMPONENT
	// =========================================================================
	function SpeakerAutocomplete(props) {
		var speakers = props.speakers || [];
		var onChange = props.onChange;
		var excludeIds = props.excludeIds || [];
		var inputRef = useRef(null);
		var suggestionsRef = useRef(null);
		var debounceRef = useRef(null);
		var _state1 = useState('');
		var inputValue = _state1[0];
		var setInputValue = _state1[1];
		var _state2 = useState([]);
		var suggestions = _state2[0];
		var setSuggestions = _state2[1];
		var _state3 = useState(false);
		var showSuggestions = _state3[0];
		var setShowSuggestions = _state3[1];
		var _state4 = useState(false);
		var isLoading = _state4[0];
		var setIsLoading = _state4[1];
		// -- Fetch attendants from REST API (debounced)
		function fetchAttendants(search) {
			if (debounceRef.current) clearTimeout(debounceRef.current);
			debounceRef.current = setTimeout(function() {
				setIsLoading(true);
				var url = 'ishf/v1/attendants';
				if (search) url += '?search=' + encodeURIComponent(search);
				wp.apiFetch({
					path: url
				}).then(function(results) {
					setSuggestions(Array.isArray(results) ? results : []);
					setShowSuggestions(true);
					setIsLoading(false);
				}).catch(function() {
					setIsLoading(false);
				});
			}, 300);
		}
		// -- Filter out already-selected attendants
		function getFilteredSuggestions() {
			return suggestions.filter(function(att) {
				return excludeIds.indexOf(att.id) === -1;
			});
		}
		// -- Handle input change
		function handleInputChange(value) {
			setInputValue(value);
			if (value.length >= 1) {
				fetchAttendants(value);
			} else {
				setSuggestions([]);
				setShowSuggestions(false);
			}
		}
		// -- Select from suggestions (store function/organization)
		function selectSuggestion(attendant) {
			var updated = speakers.slice();
			updated.push({
				id: attendant.id,
				name: attendant.name,
				function: attendant.ishf_cf_function || '',
				organization: attendant.ishf_cf_organization || '',
			});
			onChange(updated);
			setInputValue('');
			setSuggestions([]);
			setShowSuggestions(false);
		}
		// -- Add free-text speaker (Enter key)
		function handleKeyDown(e) {
			if (e.key === 'Enter' && inputValue.trim()) {
				e.preventDefault();
				var updated = speakers.slice();
				updated.push({
					id: 0,
					name: inputValue.trim()
				});
				onChange(updated);
				setInputValue('');
				setSuggestions([]);
				setShowSuggestions(false);
			}
		}
		// -- Remove a speaker
		function removeSpeaker(index) {
			var updated = speakers.slice();
			updated.splice(index, 1);
			onChange(updated);
		}

		function moveSpeakerUp(index) {
			if (index === 0) return;
			var updated = speakers.slice();
			var speaker = updated.splice(index, 1)[0];
			updated.splice(index - 1, 0, speaker);
			onChange(updated, true);
		}
		// -- Move a speaker down
		function moveSpeakerDown(index) {
			if (index === speakers.length - 1) return;
			var updated = speakers.slice();
			var speaker = updated.splice(index, 1)[0];
			updated.splice(index + 1, 0, speaker);
			onChange(updated, true);
		}
		// -- Build tag display content
		function renderTagContent(speaker) {
			// CPT attendant: bold name, then function; organization
			if (speaker.id > 0) {
				var details = [speaker.function, speaker.organization].filter(Boolean).join('; ');
				if (details) {
					return [
						el('strong', {
							key: 'name'
						}, speaker.name),
						el('span', {
							key: 'details'
						}, ', ' + details),
					];
				}
				return el('strong', null, speaker.name);
			}
			// Manual entry: bold text before first comma
			var commaIdx = speaker.name.indexOf(',');
			if (commaIdx > -1) {
				return [
					el('strong', {
						key: 'name'
					}, speaker.name.substring(0, commaIdx)),
					el('span', {
						key: 'rest'
					}, speaker.name.substring(commaIdx)),
				];
			}
			return el('strong', null, speaker.name);
		}
		// -- Close suggestions on outside click
		useEffect(function() {
			function handleClickOutside(e) {
				if (suggestionsRef.current && !suggestionsRef.current.contains(e.target) && inputRef.current && !inputRef.current.contains(e.target)) {
					setShowSuggestions(false);
				}
			}
			document.addEventListener('mousedown', handleClickOutside);
			return function() {
				document.removeEventListener('mousedown', handleClickOutside);
			};
		}, []);
		var filteredSuggestions = getFilteredSuggestions();
		return el('div', {
			className: 'ishf-speaker-autocomplete'
		}, [
			// -- Speaker tags (stacked vertically)
			speakers.length > 0 && el('div', {
				key: 'tags',
				className: 'ishf-speaker-tags'
			}, speakers.map(function(speaker, idx) {
				return el('div', {
					key: 'speaker-' + idx,
					className: 'ishf-speaker-tag' + (speaker.id > 0 ? ' ishf-speaker-tag--linked' : ''),
				}, [
					el('span', {
						key: 'content',
						className: 'ishf-speaker-tag__content'
					}, renderTagContent(speaker)),
					el(Button, {
						key: 'up',
						icon: 'arrow-up-alt2',
						label: 'Move up',
						isSmall: true,
						onClick: function() {
							moveSpeakerUp(idx);
						},
						className: 'ishf-session-row__move-btn',
					}),
					el(Button, {
						key: 'down',
						icon: 'arrow-down-alt2',
						label: 'Move down',
						isSmall: true,
						onClick: function() {
							moveSpeakerDown(idx);
						},
						className: 'ishf-session-row__move-btn',
					}),
					el('button', {
						key: 'remove',
						type: 'button',
						className: 'ishf-speaker-tag__remove',
						onClick: function() {
							removeSpeaker(idx);
						},
						'aria-label': 'Remove ' + speaker.name,
					}, '×'),
				]);
			})),
			// -- Input with suggestions dropdown
			el('div', {
				key: 'input-wrapper',
				className: 'ishf-speaker-input-wrapper',
				ref: inputRef
			}, [
				el('input', {
					key: 'input',
					type: 'text',
					className: 'components-text-control__input ishf-speaker-input',
					placeholder: 'Type a name...',
					value: inputValue,
					onChange: function(e) {
						handleInputChange(e.target.value);
					},
					onKeyDown: handleKeyDown,
					onFocus: function() {
						if (inputValue.length >= 1) fetchAttendants(inputValue);
						else fetchAttendants('');
					},
				}),
				isLoading && el('span', {
					key: 'loading',
					className: 'ishf-speaker-loading'
				}, '...'),
			]),
			// -- Suggestions dropdown
			showSuggestions && filteredSuggestions.length > 0 && el('ul', {
				key: 'suggestions',
				className: 'ishf-speaker-suggestions',
				ref: suggestionsRef,
			}, filteredSuggestions.map(function(att) {
				var details = [att.ishf_cf_function, att.ishf_cf_organization].filter(Boolean).join(', ');
				return el('li', {
					key: 'att-' + att.id,
					className: 'ishf-speaker-suggestion',
					onMouseDown: function(e) {
						e.preventDefault();
						selectSuggestion(att);
					},
				}, [
					el('strong', {
						key: 'name',
						className: 'ishf-speaker-suggestion__name'
					}, att.name),
					details && el('span', {
						key: 'details',
						className: 'ishf-speaker-suggestion__details'
					}, details),
				]);
			})),
		]);
	}
	// =========================================================================
	//  SESSION ROW COMPONENT
	// =========================================================================
	function SessionRow(props) {
		var session = props.session;
		var index = props.index;
		var total = props.total;
		var renderMode = props.renderMode;
		var onUpdate = props.onUpdate;
		var onRemove = props.onRemove;
		var onMoveUp = props.onMoveUp;
		var onMoveDown = props.onMoveDown;
		// -- Collect all selected IDs for exclusion from suggestions
		var allSelectedIds = (session.speakers || []).concat(session.moderator || []).filter(function(s) {
			return s.id > 0;
		}).map(function(s) {
			return s.id;
		});
		return el('div', {
			className: 'ishf-session-row'
		}, [
			// -- Drag handle / Move buttons
			el('div', {
				key: 'actions',
				className: 'ishf-session-row__actions'
			}, [
				el(Button, {
					key: 'up',
					icon: 'arrow-up-alt2',
					label: 'Move up',
					isSmall: true,
					disabled: index === 0,
					onClick: function() {
						onMoveUp(index);
					},
					className: 'ishf-session-row__move-btn',
				}),
				el(Button, {
					key: 'down',
					icon: 'arrow-down-alt2',
					label: 'Move down',
					isSmall: true,
					disabled: index === total - 1,
					onClick: function() {
						onMoveDown(index);
					},
					className: 'ishf-session-row__move-btn',
				}),
				el('span', {
					key: 'number',
					className: 'ishf-session-row__number'
				}, '#' + (index + 1)),
			]),
			// -- Time + Keynote (side by side)
			el('div', {
				key: 'fields',
				className: 'ishf-session-row__fields'
			}, [
				el('div', {
					key: 'time',
					className: 'ishf-session-row__time'
				}, el(TextControl, {
					label: 'Time',
					placeholder: '09:00',
					value: session.time || '',
					onChange: function(val) {
						onUpdate(index, 'time', val);
					},
				})),
				el('div', {
					key: 'keynote',
					className: 'ishf-session-row__keynote'
				}, el(TextControl, {
					label: 'Keynote Title',
					placeholder: 'Session title...',
					value: session.keynote || '',
					onChange: function(val) {
						onUpdate(index, 'keynote', val);
					},
				})),
				renderMode === 'split' && el('div', {
					key: 'partId',
					className: 'ishf-session-row__part'
				}, el(SelectControl, {
					label: 'Part (Tab)',
					value: session.partId || '',
					options: [{
						label: 'Auto (Based on time)',
						value: ''
					}].concat(
						(props.parts || []).map(function(p) {
							return {
								label: p.label || p.id,
								value: p.id
							};
						})),
					onChange: function(val) {
						onUpdate(index, 'partId', val);
					},
				})),
			]),
			// -- Speakers
			el('div', {
				key: 'speakers',
				className: 'ishf-session-row__speakers'
			}, [
				el('div', {
					key: 'speakers-header',
					style: {
						display: 'flex',
						justifyContent: 'space-between',
						alignItems: 'center'
					}
				}, [
					el('label', {
						key: 'label',
						className: 'components-base-control__label'
					}, 'Speakers'),
					el(wp.components.ToggleControl, {
						key: 'toggle',
						label: 'Sort alphabetically',
						checked: session.speakersAlphabetical !== false,
						onChange: function(val) {
							onUpdate(index, 'speakersAlphabetical', val);
						}
					})
				]),
				el(SpeakerAutocomplete, {
					key: 'autocomplete',
					speakers: session.speakers || [],
					excludeIds: allSelectedIds,
					onChange: function(newSpeakers, isManualMove) {
						var additional = {};
						if (isManualMove) {
							additional.speakersAlphabetical = false;
						}
						onUpdate(index, 'speakers', newSpeakers, additional);
					},
				}),
			]),
			// -- Moderator
			el('div', {
				key: 'moderator',
				className: 'ishf-session-row__moderator'
			}, [
				el('label', {
					key: 'label',
					className: 'components-base-control__label'
				}, 'Moderator'),
				el(SpeakerAutocomplete, {
					key: 'autocomplete',
					speakers: session.moderator || [],
					excludeIds: allSelectedIds,
					onChange: function(newModerator) {
						onUpdate(index, 'moderator', newModerator);
					},
				}),
			]),
			// -- Remove button
			el(Button, {
				key: 'remove',
				icon: 'trash',
				label: 'Remove session',
				isSmall: true,
				isDestructive: true,
				onClick: function() {
					onRemove(index);
				},
				className: 'ishf-session-row__remove',
			}),
		]);
	}
	// =========================================================================
	//  PARTS EDITOR COMPONENT
	// =========================================================================
	function PartsEditor(props) {
		var parts = props.parts || [];
		var onChange = props.onChange;

		function addPart() {
			var updated = parts.slice();
			updated.push({
				id: 'part-' + Date.now(),
				label: 'New Part',
				limit: '12:00'
			});
			onChange(updated);
		}

		function updatePart(index, field, value) {
			var updated = parts.slice();
			updated[index] = Object.assign({}, updated[index]);
			updated[index][field] = value;
			onChange(updated);
		}

		function removePart(index) {
			var updated = parts.slice();
			updated.splice(index, 1);
			onChange(updated);
		}
		return el('div', {
			className: 'ishf-seminar-parts-editor uk-margin-bottom'
		}, [
			el('h5', null, 'Program Parts (Tabs)'),
			parts.map(function(part, index) {
				return el('div', {
					key: part.id,
					style: {
						display: 'flex',
						gap: '10px',
						alignItems: 'center',
						marginBottom: '10px'
					}
				}, [
					el(TextControl, {
						label: 'Label',
						value: part.label,
						onChange: function(val) {
							updatePart(index, 'label', val);
						},
						style: {
							flex: 1,
							margin: 0
						}
					}),
					el(TextControl, {
						label: 'Time Limit (e.g. 13:00)',
						value: part.limit,
						onChange: function(val) {
							updatePart(index, 'limit', val);
						},
						style: {
							width: '120px',
							margin: 0
						}
					}),
					el(Button, {
						icon: 'trash',
						isSmall: true,
						isDestructive: true,
						onClick: function() {
							removePart(index);
						},
						style: {
							marginTop: '20px'
						}
					})
				]);
			}),
			el(Button, {
				icon: 'plus',
				isSmall: true,
				variant: 'secondary',
				onClick: addPart
			}, 'Add Part')
		]);
	}
	// =========================================================================
	//  EDIT COMPONENT
	// =========================================================================
	function EditComponent(props) {
		var attributes = props.attributes;
		var setAttributes = props.setAttributes;
		var sessions = attributes.sessions || [];
		var renderMode = attributes.renderMode || 'full';
		var parts = attributes.parts || [];
		// -- Auto-generate blockId on mount
		useEffect(function() {
			if (!attributes.blockId) {
				setAttributes({ blockId: 'program-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6) });
			}
		}, []);
		// -- Add session
		function addSession() {
			var updated = sessions.slice();
			updated.push({
				time: '',
				keynote: '',
				moderator: [],
				speakers: []
			});
			setAttributes({
				sessions: updated
			});
		}

		function updateSession(index, field, value, extraFields) {
			var updated = sessions.map(function(s, i) {
				if (i !== index) return s;
				var copy = {};
				for (var k in s) copy[k] = s[k];
				copy[field] = value;
				if (extraFields) {
					for (var k in extraFields) copy[k] = extraFields[k];
				}
				return copy;
			});
			setAttributes({
				sessions: updated
			});
		}
		// -- Remove session
		function removeSession(index) {
			var updated = sessions.slice();
			updated.splice(index, 1);
			setAttributes({
				sessions: updated
			});
		}
		// -- Move session up
		function moveUp(index) {
			if (index === 0) return;
			var updated = sessions.slice();
			var temp = updated[index];
			updated[index] = updated[index - 1];
			updated[index - 1] = temp;
			setAttributes({
				sessions: updated
			});
		}
		// -- Move session down
		function moveDown(index) {
			if (index >= sessions.length - 1) return;
			var updated = sessions.slice();
			var temp = updated[index];
			updated[index] = updated[index + 1];
			updated[index + 1] = temp;
			setAttributes({
				sessions: updated
			});
		}
		return el('div', {
			className: 'ishf-seminar-editor'
		}, [
			// -- Block Header
			el('div', {
				key: 'header',
				className: 'ishf-seminar-editor__header'
			}, [
				el('h4', {
					key: 'title'
				}, 'ISHF Seminar Program'),
				el(SelectControl, {
					key: 'mode',
					label: 'Render Mode',
					value: renderMode,
					options: [{
						label: 'Full Program',
						value: 'full'
					}, {
						label: 'Split Seminar (Tabs)',
						value: 'split'
					}],
					onChange: function(val) {
						setAttributes({
							renderMode: val
						});
					},
					className: 'ishf-seminar-editor__mode',
				}),
				renderMode === 'split' && el(PartsEditor, {
					key: 'parts-editor',
					parts: parts,
					onChange: function(newParts) {
						setAttributes({
							parts: newParts
						});
					}
				}),
			]),
			// -- Sessions list
			el('div', {
				key: 'sessions',
				className: 'ishf-seminar-editor__sessions'
			}, sessions.map(function(session, index) {
				return el(SessionRow, {
					key: 'session-' + index,
					session: session,
					index: index,
					total: sessions.length,
					parts: parts,
					renderMode: renderMode,
					onUpdate: updateSession,
					onRemove: removeSession,
					onMoveUp: moveUp,
					onMoveDown: moveDown,
				});
			})),
			// -- Add session button
			el('div', {
				key: 'add-wrapper',
				className: 'ishf-seminar-editor__add'
			}, el(Button, {
				icon: 'plus-alt2',
				variant: 'secondary',
				onClick: addSession,
				className: 'ishf-seminar-editor__add-btn',
			}, 'Add Session')),
		]);
	}
	// =========================================================================
	//  REGISTER BLOCK
	// =========================================================================
	wp.blocks.registerBlockType('ishf/seminar-program', {
		title: 'ISHF Seminar Program',
		description: 'ISHF: A seminar program with repeatable sessions, time slots, keynote titles, and speaker autocomplete.',
		icon: 'schedule',
		category: 'ishf',
		attributes: {
			blockId: {
				type: 'string',
				default: '',
			},
			renderMode: {
				type: 'string',
				default: 'full',
			},
			parts: {
				type: 'array',
				default: [{
					id: 'part-1',
					label: 'Manhã',
					limit: '13:00'
				}, {
					id: 'part-2',
					label: 'Tarde',
					limit: '24:00'
				}],
			},
			sessions: {
				type: 'array',
				default: [],
			},
		},
		edit: EditComponent,
		save: function() {
			return null;
		},
	});
})();
