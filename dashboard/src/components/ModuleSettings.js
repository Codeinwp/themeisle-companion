/* global obfxDash */
import { useContext, useState } from '@wordpress/element';
import { ModulesContext } from './DashboardContext';
import { requestData } from '../utils/rest';
import {
	Dashicon,
	Button,
	CheckboxControl,
	RadioControl,
	ToggleControl,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import classnames from 'classnames';
import ReactHtmlParser from 'react-html-parser';

const { options, root, setSettingsRoute } = obfxDash;

const ModuleSettings = ( { slug } ) => {
	const { modulesData, setModulesData } = useContext( ModulesContext );
	const [ open, setOpen ] = useState( false );
	const [ loading, setLoading ] = useState( false );
	// eslint-disable-next-line camelcase
	const { module_settings } = modulesData;
	const [ tempData, setTempData ] = useState(
		module_settings[ slug ] ? module_settings[ slug ] : {}
	);

	const loadingIcon = (
		<Dashicon size={ 18 } icon="update" className="is-loading" />
	);

	const changeOption = ( name, newValue ) => {
		const newTemp = tempData;
		newTemp[ name ] = newValue;
		setTempData( newTemp );
	};

	const sendData = () => {
		const dataToSend = {
			slug,
			value: tempData,
		};

		requestData( root + setSettingsRoute, dataToSend ).then( ( r ) => {
			if ( r.type !== 'success' ) {
				setTempData( module_settings[ slug ] );
				setLoading( false );
				return;
			}

			module_settings[ slug ] = tempData;
			setModulesData( modulesData );
			setLoading( false );
		} );
	};

	const renderOption = ( index ) => {
		const setting = options[ slug ][ index ];
		const selectedValue = tempData[ setting.id ]
			? tempData[ setting.id ]
			: setting.default;

		switch ( setting.type ) {
			case 'checkbox':
				return (
					<CheckboxControl
						label={ setting.label }
						checked={ selectedValue === '1' }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue ? '1' : '0' )
						}
					/>
				);
			case 'radio':
				return (
					<RadioControl
						label={ setting.title }
						options={ setting.options.map( ( label, value ) => {
							return { label, value };
						} ) }
						selected={ parseInt( selectedValue ) }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue )
						}
					/>
				);
			case 'toggle':
				return (
					<ToggleControl
						label={ ReactHtmlParser( setting.label ) }
						checked={ selectedValue === '1' }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue ? '1' : '0' )
						}
					/>
				);
			case 'select':
				return (
					<SelectControl
						label={ setting.title }
						value={ parseInt( selectedValue ) }
						options={ Object.entries( setting.options ).map(
							( [ value, label ] ) => {
								return { value, label };
							}
						) }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue )
						}
					/>
				);
			case 'text':
				return (
					<TextControl
						label={ setting.title }
						value={ selectedValue }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue )
						}
					/>
				);
		}
	};

	const getContent = () => {
		const result = [];

		for ( let i = 0; i < options[ slug ].length; i++ ) {
			const element = options[ slug ][ i ];
			let row;

			if (
				element.title &&
				element.label &&
				element.title !== '' &&
				element.label !== ''
			) {
				row = <p className="title"> { options[ slug ][ i ].title } </p>;
				result.push( row );
			}

			if ( element.hasOwnProperty( 'before_wrap' ) ) {
				row = [];
				while ( true ) {
					row.push( renderOption( i ) );
					if ( options[ slug ][ i ].hasOwnProperty( 'after_wrap' ) ) {
						break;
					}

					i++;
				}

				result.push( <div className="settings-row"> { row } </div> );
				continue;
			}

			result.push( renderOption( i ) );
		}

		return result;
	};

	return (
		<div
			className={ classnames( [
				'module-settings',
				open ? 'open' : 'closed',
			] ) }
		>
			<button
				aria-expanded={ open }
				className="accordion-header"
				onClick={ () => setOpen( ! open ) }
			>
				<div className="accordion-title"> Settings </div>
				<Dashicon icon={ open ? 'arrow-up-alt2' : 'arrow-down-alt2' } />
			</button>
			{ open && (
				<div
					className={ classnames( [
						'accordion-content',
						loading ? 'loading' : '',
					] ) }
				>
					{ getContent() }
					<div className="buttons-container">
						<Button
							isSecondary
							className="obfx-button"
							onClick={ () => setOpen( false ) }
						>
							Close
						</Button>
						<Button
							isPrimary
							className="obfx-button"
							onClick={ () => {
								setLoading( true );
								sendData();
							} }
						>
							{ loading ? loadingIcon : 'Save' }
						</Button>
					</div>
				</div>
			) }
		</div>
	);
};

export default ModuleSettings;
