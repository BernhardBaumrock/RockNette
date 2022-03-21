# RockNette

## A message to Russian 🇷🇺 people

If you currently live in Russia, please read [this message](https://github.com/Roave/SecurityAdvisories/blob/latest/ToRussianPeople.md).

[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

---

Module to include Nette Framework Components into ProcessWire.

You'll get all necessary instructions on the module information screen:

![img](https://i.imgur.com/Q1bJWtD.png)

## Using Latte Template Engine

Add the latte package via composer: https://latte.nette.org/en/guide#toc-installation-and-usage

Create a test template: `/site/assets/Latte/test.latte`:

```php
<h1>{$page->title}</h1>
<ul>
  <li n:foreach="$items as $item">{$item}</li>
</ul>
```

Add this to your home.php file:

```php
$modules->get('RockNette')->load();
$latte = new \Latte\Engine;
$latte->setTempDirectory($config->paths->cache."Latte");
$dir = $config->paths->assets."Latte/";

$parameters = array_merge([
  'items' => ['one', 'two', 'three'],
], $this->wire('all')->getArray());
echo $latte->render($dir.'test.latte', $parameters);
bd($latte->renderToString($dir.'test.latte', $parameters));
die();
```
