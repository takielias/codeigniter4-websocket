[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/takielias/codeigniter4-websocket">
    <img src="https://user-images.githubusercontent.com/38932580/71325065-b6d05600-2511-11ea-9169-694185c48f8b.png" alt="Logo" width="80" height="80">
  </a>
  
  <h3 align="center">CodeIgniter 4 WebSocket Library</h3>

<a href="https://www.buymeacoffee.com/takielias" target="_blank"> <img align="left" src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" height="50" width="210" alt="takielias" /></a>
</a>
<br><br>

CodeIgniter WebSocket library. It allows you to make powerfull realtime applications by using Ratchet [Socketo.me](http://socketo.me) Websocket technology.

#### WebSocket Library for Codeigniter 3.x https://github.com/takielias/codeigniter-websocket

<!-- TABLE OF CONTENTS -->
## Table of Contents

* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
  * [Publishing](#publishing)
* [Usage](#usage)
  * [Authentication and callbacks](#authentication-and-callbacks)
* [Roadmap](#roadmap)
* [Contributing](#contributing)
* [License](#license)
* [Contact](#contact)
* [Acknowledgements](#acknowledgements)


<!-- GETTING STARTED -->
## Getting Started

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

### Prerequisites

- PHP 7.2+
- CodeIgniter Framework (4.* recommended)
- Composer
- PHP sockets extension enabled

### Installation

```sh
composer require takielias/codeigniter4-websocket @dev
```
### Publishing Resource
You need to publish the resources for the default configuration
```sh
php spark websocket:publish
```

<!-- USAGE EXAMPLES -->
## Usage
First start CodeIgniter
```sh
php spark serve
```

If you run the server in a different port, follow the command below.
```sh
PHP spark serve --port=9092
```

**Finally start Websocket Server**
```sh
php public/index.php Websocket start
```
**N.B** : Don't forget to add the following line in app/Config/Routes.php `app/Config/Routes.php`

```php
$routes->setAutoRoute(true);
```

**WOW You made it !!!** :heavy_check_mark: 

Open two pages of your project on the following URL with different IDs :

**For default Port**
`http://localhost:8080/Websocket/user/1`
`http://localhost:8080/Websocket/user/2`

**For custom Port**
`http://localhost:9092/Websocket/user/1`
`http://localhost:9092/Websocket/user/2`

<!-- Authentication & callbacks -->
## Authentication and callbacks

There are few predefined callbacks, here's the list :

` auth, event, close, citimer, roomjoin, roomleave, roomchat `

Please check Websocket.php controller To get the Defining example of various Callback Function

```sh
    public function start()
    {
        $ws = service('CodeigniterWebsocket');
        $ws->set_callback('auth', array($this, '_auth'));
        $ws->set_callback('event', array($this, '_event'));
        $ws->run();
    }

    public function _auth($datas = null)
    {
        // Here you can verify everything you want to perform user login.

        return (!empty($datas->user_id)) ? $datas->user_id : false;
    }

    public function _event($datas = null)
    {
        // Here you can do everything you want, each time message is received 
        echo 'Hey ! I\'m an EVENT callback' . PHP_EOL;
    }

 ```   
Two Callback functions have been defined in the above example. First One is **auth** & the Second one is **event**.
 
###### 🔨🔨🔨 If you need to customize Callback function, Please check the CodeigniterWebsocker.php config file in Your config directory.


<!-- ROADMAP -->
## Roadmap

See the [open issues](https://github.com/takielias/codeigniter4-websocket/issues) for a list of proposed features (and known issues).


<!-- CONTRIBUTING -->
## Contributing

Contributions are what makes the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request


<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.

<!-- CONTACT -->
## Contact

Taki Elias - [@takiele](https://twitter.com/takiele) - [https://ebuz.xyz](https://ebuz.xyz) - taki.elias@gmail.com

<!-- ACKNOWLEDGEMENTS -->
## Acknowledgements
* [http://socketo.me](https://github.com/ratchetphp/Ratchet)
* [Websocket Client for PHP](https://github.com/Textalk/websocket-php)
* [Choose an Open Source License](https://choosealicense.com)
* [GitHub Pages](https://pages.github.com)
* [Animate.css](https://daneden.github.io/animate.css)
* [Loaders.css](https://connoratherton.com/loaders)
* [Smooth Scroll](https://github.com/cferdinandi/smooth-scroll)
* [Font Awesome](https://fontawesome.com)


## Support on Buy Me A Coffee
Hey dude! Help me out for a cup of ☕!

<a href="https://www.buymeacoffee.com/takielias" target="_blank"> <img align="left" src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" height="50" width="210" alt="takielias" /></a>
</a>
<br><br>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/takielias/codeigniter4-websocket.svg?style=flat-square
[contributors-url]: https://github.com/takielias/codeigniter4-websocket/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/takielias/codeigniter4-websocket.svg?style=flat-square
[forks-url]: https://github.com/takielias/codeigniter4-websocket/network/members
[stars-shield]: https://img.shields.io/github/stars/takielias/codeigniter4-websocket.svg?style=flat-square
[stars-url]: https://github.com/takielias/codeigniter4-websocket/stargazers
[issues-shield]: https://img.shields.io/github/issues/takielias/codeigniter4-websocket.svg?style=flat-square
[issues-url]: https://github.com/takielias/codeigniter4-websocket/issues
[license-shield]: https://img.shields.io/github/license/takielias/codeigniter4-websocket.svg?style=flat-square
[license-url]: https://github.com/takielias/codeigniter4-websocket/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=flat-square&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/takielias
[product-screenshot]: images/screenshot.png

[ico-version]: https://img.shields.io/packagist/v/takielias/codeigniter4-websocket.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/takielias/codeigniter4-websocket.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/takielias/codeigniter4-websocket
[link-downloads]: https://packagist.org/packages/takielias/codeigniter4-websocket
[link-author]: https://github.com/takielias
