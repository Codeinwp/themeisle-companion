/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

import classnames from 'classnames'

const {
  registerBlockType
} = wp.blocks;

const { RichText } = wp.editor;
const { Fragment } = wp.element;

registerBlockType( 'orbitfox/notice', {
  title: __( 'Notice' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'notice',
    'info'
  ],
  attributes: {
    type: {
      source: 'attribute',
      selector: '.obfx-block-notice',
      attribute: 'data-type',
      default: 'info',
    },
    title: {
      source: 'text',
      type: 'string',
      selector: '.obfx-block-notice__title',
      default: 'Info',
    },
    content: {
      type: 'array',
      source: 'children',
      selector: '.obfx-block-notice__content',
    },
  },
  edit: props => {

    const { attributes: { type, content, title }, className, setAttributes } = props

    // @TODO Add a toolbar control and create a custom svg icon for this block
    return (
      <Fragment>

        <div className={ classnames( className, `${className}--${type}` ) }>

          <RichText
            tagName="p"
            value={ title }
            className='obfx-block-notice__title'
            onChange={ title => setAttributes( { title } ) }
          />

          <RichText
            tagName="p"
            placeholder={ __( 'Your tip/warning content' ) }
            value={ content }
            className='obfx-block-notice__content'
            onChange={ content => setAttributes( { content } ) }
            keepPlaceholderOnFocus="true"
          />
        </div>

      </Fragment>
    )
  },
  save: props => {

    const { type, title, content } = props.attributes

    return (
      <div className={ `obfx-block-notice--${ type }` } data-type={ type }>
        <p className='obfx-block-notice__title'>{ title }</p>
        <p className='obfx-block-notice__content'>{ content }</p>
      </div>
    )
  },
} );