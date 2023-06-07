import { flare } from '@flareapp/flare-client';

//  Load Flare key from .env file, injected by Webpack
if (window.FLARE_KEY) {
  flare.light(window.FLARE_KEY);
}
