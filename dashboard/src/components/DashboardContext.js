/* global obfxDash */
import { createContext } from '@wordpress/element';

export const ModulesContext = createContext( obfxDash.data );
export const PluginsContext = createContext( null );
