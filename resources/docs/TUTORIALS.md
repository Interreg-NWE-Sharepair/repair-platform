## How to implement the Tutorials embed

### Embed snippet
To include the tutorials on your webpage, just include the following iframe.

To just include the embed as is:
```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials"></iframe>
```

### Adding your own style
If you want to include your own styling you can add a style parameter to the url `&style=testaankoop`  

We have the following default variables available:
- testaankoop
- repairtogether
- ...

But you can also include your own stylesheet url
`?style=https://guidance.sharepair.org/repgui/css/main.css`

```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials?style=testaankoop"></iframe>
```


### Disable the filters

If you only want to add the tutorials without any filters, you can disable those by adding the parameter `filters=false` to the url.
This will remove the whole block on top and just shows all tutorials.
```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials?filters=false"></iframe>
```

#### Disable search
You can turn off the search form by adding the following parameter
`&searchSetting=false`
```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials?filters=false&searchSetting=false"></iframe>
```

### Show a specific set of tutorials
If you only want to show the tutorials of a specific product type, you can include that type to the url (and e.g. don't show the filters)

```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials?type=2b16a416-b10b-4f13-b4ff-b67ec7366c52&filters=false"></iframe>
```

### Show or hide external guides e.g. iFixit
`&externalGuides=false`
```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials?type=2b16a416-b10b-4f13-b4ff-b67ec7366c52&externalGuides=false"></iframe>
```

You can turn off the external guides blocks by adding a parameter for that


### Adding locale
You can add the locale to the snippet to change language
 `&lang=nl`

```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials&lang=nl"></iframe>
```


### Adding target
If you want to let the iframe open the detail page into the same iframe you can add the `?target=self` query parameter

```html
<iframe title="Tutorials overview" id="repair-tutorials-embed" frameborder="0" scrolling="no" src="https://guidance.sharepair.org/api/guidance/tutorials&lang=nl&target=self"></iframe>
```

The options to chose from are: 
- self -> stays in the same iframe
- blank -> this will open in a new tab
- If you don't add a target `_top` will be added as default. This will open the detail page in the same window.

### Iframe resizer
It's recommended to include [iframe-resizer](https://github.com/davidjbradshaw/iframe-resizer) in your application to handle proper resizing of the component.
This will make sure the iframe will always have the correct height and will be responsive.

```
<script src="https://unpkg.com/iframe-resizer@4.3.2/js/iframeResizer.min.js"></script>
<script>
  iFrameResize({}, '#repair-tutorials-embed');
</script>
```
