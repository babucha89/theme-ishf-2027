wp.blocks.registerBlockType('ishf/quotation', {
	title: 'ISHF Quotation',
	description: 'ISHF: Block for inserting a quotation, its author, function and entity.',
	icon: 'format-quote',
	category: 'common',
	attributes: {
		blockId: {
			type: 'string',
			default: ''
		},
		quotation: {
			type: 'string',
			default: ''
		},
		author: {
			type: 'string',
			default: ''
		},
		function: {
			type: 'string',
			default: ''
		},
		entity: {
			type: 'string',
			default: ''
		},
	},
	edit: EditComponent,
	save: () => null
});

function EditComponent(props) {
	const { attributes, setAttributes } = props;
	var useEffect = wp.element.useEffect;

	// -- Auto-generate blockId on mount
	useEffect(function() {
		if (!attributes.blockId) {
			setAttributes({ blockId: 'quote-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6) });
		}
	}, []);

	function updateQuotation(newValue) {
		setAttributes({ quotation: newValue });
	}

	function updateAuthor(newValue) {
		setAttributes({ author: newValue });
	}

	function updateFunction(newValue) {
		setAttributes({ function: newValue });
	}
	function updateEntity(newValue) {
		setAttributes({ entity: newValue });
	}

	return wp.element.createElement(
		'div',
		{ className: 'ishf-quotation-editor' },
		[
			// -- Block Title
			wp.element.createElement(
				'h4',
				null,
				'ISHF Quotation'		
			),

			// -- Block Elements
			wp.element.createElement(
				wp.components.TextareaControl,
				{
					label: 'Quotation',
					value: attributes.quotation,
					onChange: updateQuotation
				}
			),
			wp.element.createElement(
				wp.components.TextControl,
				{
					label: 'Author',
					value: attributes.author,
					onChange: updateAuthor,
				}
			),
			wp.element.createElement(
				wp.components.TextControl,
				{
					label: 'Function',
					value: attributes.function,
					onChange: updateFunction,
				}
			),
			wp.element.createElement(
				wp.components.TextControl,
				{
					label: 'Entity',
					value: attributes.entity,
					onChange: updateEntity,
				}
			),
		]
	);
}