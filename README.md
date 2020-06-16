# auditor-bundle [![Tweet](https://img.shields.io/twitter/url/http/shields.io.svg?style=social)](https://twitter.com/intent/tweet?text=Create%20audit%20logs%20for%20all%20Doctrine%20ORM%20database%20related%20changes%20with%20DoctrineAuditBundle.&url=https://github.com/DamienHarper/auditor-bundle&hashtags=auditor-bundle)
[![Latest Stable Version](https://poser.pugx.org/damienharper/auditor-bundle/v/stable)](https://packagist.org/packages/damienharper/auditor-bundle)
[![Latest Unstable Version](https://poser.pugx.org/damienharper/auditor-bundle/v/unstable)](https://packagist.org/packages/damienharper/auditor-bundle)
[![Build Status](https://travis-ci.com/DamienHarper/auditor-bundle.svg?branch=master)](https://travis-ci.com/DamienHarper/auditor-bundle)
[![License](https://poser.pugx.org/damienharper/auditor-bundle/license)](https://packagist.org/packages/damienharper/auditor-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DamienHarper/auditor-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DamienHarper/auditor-bundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/damienharper/auditor-bundle/downloads)](https://packagist.org/packages/damienharper/auditor-bundle)
[![Monthly Downloads](https://poser.pugx.org/damienharper/auditor-bundle/d/monthly)](https://packagist.org/packages/damienharper/auditor-bundle)
[![Daily Downloads](https://poser.pugx.org/damienharper/auditor-bundle/d/daily)](https://packagist.org/packages/damienharper/auditor-bundle)

`auditor-bundle`, formerly known as `DoctrineAuditBundle` integrates `auditor` library into any Symfony 3.4+ application.

You can try out this bundle by cloning its companion demo app. 
Follow instructions at [auditor-bundle-demo](https://github.com/DamienHarper/auditor-bundle-demo).


## Official Documentation
`auditor-bundle` official documentation can be found [here](doc/00-index.md).


## Version Information
 Version   | Status                      | PHP requirements | Symfony requirements | Badges
:----------|:----------------------------|:-----------------|:---------------------|:-----------
 4.x       | Active development :rocket: | >= 7.2           | >= 3.4               | [![Build Status](https://travis-ci.com/DamienHarper/auditor-bundle.svg?branch=master)](https://travis-ci.com/DamienHarper/auditor-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DamienHarper/auditor-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DamienHarper/auditor-bundle/?branch=master)
 3.x       | Active support :rocket:     | >= 7.1           | >= 3.4               | [![Build Status](https://travis-ci.com/DamienHarper/auditor-bundle.svg?branch=3.x)](https://travis-ci.com/DamienHarper/auditor-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DamienHarper/auditor-bundle/badges/quality-score.png?b=3.x)](https://scrutinizer-ci.com/g/DamienHarper/auditor-bundle/?branch=3.x)
 2.x       | End of life                 | >= 7.1           | >= 3.4               |
 1.x       | End of life                 | >= 7.1           | >= 3.4               |

Changelog is available [here](CHANGELOG.md)


## Usage
Once [installed](doc/11-installation.md) and [configured](doc/20-general-configuration.md), any database change 
affecting audited entities will be logged to audit logs automatically.
Also, running schema update or similar will automatically setup audit logs for every 
new auditable entity.


## Contributing
`auditor-bundle` is an open source project. Contributions made by the community are welcome. 
Send me your ideas, code reviews, pull requests and feature requests to help us improve this project.

Do not forget to provide unit tests when contributing to this project. 
To do so, follow instructions in this dedicated [README](tests/README.md)


## Credits
- Thanks to [all contributors](https://github.com/DamienHarper/auditor-bundle/graphs/contributors)


## License
`auditor-bundle` is free to use and is licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php)
