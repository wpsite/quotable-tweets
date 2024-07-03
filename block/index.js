import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import Edit from './edit';
import save from './save';
import './style.scss'; // For the front-end.
import './editor.scss'; // For the editor.

registerBlockType('draftpress/quotable-tweets', {
    apiVersion: 2,
    title: __('Quotable Tweets', 'quotable-tweets'),
    icon: 'twitter',
    description: __('Beautiful way to display an actionable tweet.', 'quotable-tweets'),
    category: 'widgets',
    attributes: {
        title: {
            type: 'string',
            default: __('Share this article!', 'quotable-tweets'),
        },
        bitlyAccessToken: {
            type: 'string',
            default: '',
        },
        buttonText: {
            type: 'string',
            default: __('Tweet', 'quotable-tweets'),
        },
    },
    edit: Edit,
    save,
});
