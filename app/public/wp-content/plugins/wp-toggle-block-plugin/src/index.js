const { registerBlockType } = wp.blocks;
const { ToggleControl } = wp.components;
const { InspectorControls, InnerBlocks } = wp.blockEditor;
const { useState } = wp.element;

registerBlockType('wp-toggle-block/toggle-block', {
    title: 'Toggle Paragraph/Shortcode Block',
    icon: 'visibility',
    category: 'layout',
    attributes: {
        showFirstBlock: {
            type: 'boolean',
            default: true,
        },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const { showFirstBlock } = attributes;

        const toggleBlock = () => {
            setAttributes({ showFirstBlock: !showFirstBlock });
        };

        return (
            <>
                <InspectorControls>
                    <ToggleControl
                        label="Toggle Block"
                        checked={showFirstBlock}
                        onChange={toggleBlock}
                    />
                </InspectorControls>
                <div className="toggle-block-container">
                    <button className="toggle-button" onClick={toggleBlock}>
                        {showFirstBlock ? 'Switch to Shortcode Block' : 'Switch to Paragraph Block'}
                    </button>
                    <div className="block-content">
                        {showFirstBlock ? (
                            <InnerBlocks allowedBlocks={['core/paragraph']} />
                        ) : (
                            <InnerBlocks allowedBlocks={['core/shortcode']} />
                        )}
                    </div>
                </div>
            </>
        );
    },
    save: (props) => {
        const { attributes } = props;
        const { showFirstBlock } = attributes;

        return (
            <div className="toggle-block-container">
                <button className="toggle-button">
                    {showFirstBlock ? 'Switch to Shortcode Block' : 'Switch to Paragraph Block'}
                </button>
                <div className="block-content">
                    <div className={showFirstBlock ? 'block-show' : 'block-hide'}>
                        <InnerBlocks.Content />
                    </div>
                    <div className={showFirstBlock ? 'block-hide' : 'block-show'}>
                        <InnerBlocks.Content />
                    </div>
                </div>
            </div>
        );
    },
});