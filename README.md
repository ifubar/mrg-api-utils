## Api utilities meetings Magora guidelines

__Includes:__
- Filter
- Sorter

__Installation:__

composer.json
```shell
"require": {
    "ifubar/mrg-api-utils": "*"
},
"repositories": [
    { "type": "vcs", "url": "https://github.com/ifubar/mrg-api-utils" }
],
"minimum-stability": "dev"
```

```shell
composer install
```

__Usage:__
```shell
$users = User::query();
(new Filter('email eq foo@bar.baz | age gt 18'))->apply($users);
$users->get();
```
