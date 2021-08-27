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

	const loadingIcon = (
		<Dashicon size={ 18 } icon="update" className="is-loading" />
	);

	const [ tempData, setTempData ] = useState( module_settings[ slug ] );

	const changeOption = ( name, newValue ) => {
		// const newTempData = tempData;
		tempData[ name ] = newValue;
		setTempData( tempData );
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

		switch ( setting.type ) {
			case 'checkbox':
				return (
					<CheckboxControl
						label={ setting.label }
						checked={ tempData[ setting.id ] === '1' }
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
						selected={ parseInt( tempData[ setting.id ] ) }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue )
						}
					/>
				);
			case 'toggle':
				return (
					<ToggleControl
						label={ ReactHtmlParser( setting.label ) }
						checked={ tempData[ setting.id ] === '1' }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue ? '1' : '0' )
						}
					/>
				);
			case 'select':
				return (
					<SelectControl
						label={ setting.title }
						value={ parseInt( tempData[ setting.id ] ) }
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
						value={ tempData[ setting.id ] }
						onChange={ ( newValue ) =>
							changeOption( setting.id, newValue )
						}
					/>
				);
		}
	};

	const getContent = () => {
		// TODO
		return Object.keys( options[ slug ] ).map( ( index ) =>
			renderOption( index )
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
