import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { title, bitlyAccessToken, buttonText } = attributes;

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <PanelBody title={__('Settings', 'quotable-tweets')}>
                    <TextControl
                        label={__('Title', 'quotable-tweets')}
                        value={title}
                        onChange={(title) => setAttributes({ title })}
                    />
                    <TextControl
                        label={__('Bitly Access Token', 'quotable-tweets')}
                        value={bitlyAccessToken}
                        onChange={(bitlyAccessToken) =>
                            setAttributes({ bitlyAccessToken })
                        }
                    />
                    <TextControl
                        label={__('Button Text', 'quotable-tweets')}
                        value={buttonText}
                        onChange={(buttonText) =>
                            setAttributes({ buttonText })
                        }
                    />
                </PanelBody>
            </InspectorControls>
            <div className="nnr-qt-container">
                <div className="nnr-qt-title-container">
                    <span aria-hidden="true" className="nnr-qt-icon-twitter"></span>
                    <span className="nnr-qt-title">{title}</span>
                </div>
                <div className="nnr-qt-text-container">
                    <p className="nnr-qt-post-title">{__('Testing Author List', 'quotable-tweets')}</p>
                    <p className="nnr-qt-quote-container">
                        <span className="nnr-qt-quote dashicons dashicons-format-quote"></span>
                    </p>
                </div>
                <a className="nnr-qt-button" href="#">
                    {buttonText}
                </a>
            </div>
        </div>
    );
}
