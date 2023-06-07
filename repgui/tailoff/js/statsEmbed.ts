'use strict';

/**
 * Essential components
 */

import { GeneralComponent } from './components/general.component';
new GeneralComponent();

import { WebfontComponent } from './components/webfont.component';
new WebfontComponent([
  'https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;0,700;0,900;1,400;1,600;1,700&display=swap'
]);

import { TooltipComponent } from './components/tooltip.component';
new TooltipComponent();

import './components/lazySizes.component';

import './components/iframeResizer';

/**
 * CSS import
 * DO NOT REMOVE !!
 */
import '../css/stats.css';
