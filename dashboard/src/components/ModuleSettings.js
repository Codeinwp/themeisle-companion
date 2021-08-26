/* global obfxDash */
import { useContext, useState } from '@wordpress/element';
import { ModulesContext } from './DashboardContext';
import { requestData } from '../utils/rest';
import {
	CheckboxControl,
	RadioControl,
	Dashicon,
	ToggleControl,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import ReactHtmlParser from 'react-html-parser';
import classnames from 'classnames';

const { options, root, setSettingsRoute } = obfxDash;

const ModuleSettings = ( { slug } ) => {
	const { modulesData, setModulesData } = useContext( ModulesContext );
	const [ open, setOpen ] = useState( false );
	const [ loadingOption, setLoadingOption ] = useState( null );
	// eslint-disable-next-line camelcase
	const { module_settings } = modulesData;

	const changeOption = ( name, newValue ) => {
		setLoadingOption( name );
		module_settings[ slug ][ name ] = newValue;
		const dataToSend = {
			slug,
			value: module_settings[ slug ],
		};

		requestData( root + setSettingsRoute, dataToSend ).then( ( r ) => {
			if ( r.type !== 'success' ) {
				setLoadingOption( null );
				return;
			}

			module_settings[ slug ][ name ] = newValue;
			setModulesData( modulesData );
			setLoadingOption( null );
		} );
	};

	const renderOption = ( index ) => {
		const setting = options[ slug ][ index ];
		const selected = module_settings[ slug ][ setting.id ];
		const loadingClass = loadingOption === setting.id ? 'loading' : '';

		switch ( setting.type ) {
			case 'checkbox':
				return (
					<CheckboxControl
						className={ loadingClass }
						label={ setting.label }
						checked={ selected === '1' }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue ? '1' : '0' )
						}
					/>
				);
			case 'radio':
				return (
					<RadioControl
						className={ loadingClass }
						label={ setting.title }
						options={ setting.options.map( ( label, value ) => {
							return { label, value };
						} ) }
						selected={ parseInt( selected ) }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue )
						}
					/>
				);
			case 'toggle':
				return (
					<ToggleControl
						className={ loadingClass }
						label={ ReactHtmlParser( setting.label ) }
						checked={ selected === '1' }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue ? '1' : '0' )
						}
					/>
				);
			case 'select':
				return (
					<SelectControl
						className={ loadingClass }
						label={ setting.title }
						value={ parseInt( selected ) }
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
						className={ loadingClass }
						label={ setting.title }
						value={ selected }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue )
						}
					/>
				);
		}
	};

	const getContent = () => {
		// TODO
		return (
			<div className="accordion-content">
				{ Object.keys( options[ slug ] ).map( ( index ) =>
					renderOption( index )
				) }
			</div>
		);
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
			{ open && getContent() }
		</div>
	);
};

export default ModuleSettings;
