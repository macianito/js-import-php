# [JS PHP Import](https://github.com/macianito/js-import-php/)

**JS PHP Import** imports php functions and classes to javascript environment thus they can be accessed as javascript functions and methods of a class

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

Download the [code](https://github.com/macianito/js-import-php/) that is in the *php_server* folder of the project and include it in your HTML. 

```html
<script type="text/javascript" src="path_to_php_server/?app=path_to_app"></script>
```

## Examples

We have several examples [on the website](https://mazius.org/). Here are some examples to get you started:
These examples will put the result of each called php function into a container on the page.

```jsx
$scandir(PATH_TO_FOLDER).then(function(result) {
  resultObj.html(result.join('<br>'));
});

$system('ls -l').then(function(result) {
  resultObj.append(result);
});

$str_replace(' ').then(function(result) {
  resultObj.append(result);
});

```

You could call a php function using the result of a previous called php function as a parameter, thus concatenating several function calls.
Here is an example of this.

```jsx
$testfn(34, 56).exec(function(result) { // has de posar o no les cometes
   return $testfn(34, result);
}).exec(function(result) { // has de posar o no les cometes
   resultObj.append('result: ' + result + '<br><br>');
});
```


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
