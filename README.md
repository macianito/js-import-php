# [JS PHP Import](https://github.com/macianito/js-import-php/)

**JS PHP Import** imports php functions and classes to javascript environment thus they can be accessed as javascript functions

## Table of contents

- [Environments in which to use JS PHP Import](#environments-in-which-to-use-js-php-import)
- [Requires](#requires)
- [Usage](#usage)
- [Examples](#bugs-and-feature-requests)
- [Live Example](#live-example)
- [License](#license)
- [Author](#author)
- [Contact Me](#contact-me)


## Environments in which to use JS PHP Import

- Web Browser

## Requires

- PHP
- Javascript browser support

## Usage

Download the [code](https://github.com/macianito/js-import-php/) and include it in your HTML. 

```html
<script type="text/javascript" src="path_to_php_server/php_server/?app=path_to_app"></script>
```

## Examples

We have several examples [on the website](https://reactjs.org/). Here is the first one to get you started:

```jsx
$scandir(FOLDER_PATH).then(function(result) {
  resultObj.html(result.join('<br>'));
});

$system('ls -l').then(function(result) {
  resultObj.append(result);
});

$str_replace(' ').then(function(result) {
  resultObj.append(result);
});

```

This example will put the result of each called php function into a container on the page.

## Live Example

[http://phplandia.org/php_server/index.html](http://phplandia.org/php_server/index.html)


## License

**JS PHP Import** is [MIT licensed](./LICENSE).

## Author

**Ivan Macià**
- <http://mazius.org>
- <https://twitter.com/vil_cohaagen>


## Contact Me  
  
- Email Me: ivan@mazius.org
