import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function save({ attributes }) {
    const { title, buttonText } = attributes;

    return (
        <div {...useBlockProps.save()}>
            <div className="nnr-qt-container">
                <div className="nnr-qt-title-container">
                    <span className="nnr-qt-icon-twitter" aria-hidden="true"></span>
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
