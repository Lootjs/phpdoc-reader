# PhpDocReader

## usage

```php
    $class = \App\Models\User::class;
    $method = 'getChild';
    $comment = (new \ReflectionMethod($class, $method))->getDocComment();
    $res = new Loot\PhpDocReader\PhpDocReader($comment);

    var_dump($res->getAnnotationsByName('@param'));
``` 

or just 

```php
    $comment = '
/**
 * @param int $var Description
 */';
    $res = new Loot\PhpDocReader\PhpDocReader($comment);

    var_dump($res->getAnnotation('@param')->getDescription());
``` 

## Classes
### Class PhpDocReader

#### Method getAnnotation
usage

```php
    $res = new Loot\PhpDocReader\PhpDocReader('
/**
  * @return int
  */');

    var_dump($res->getAnnotation('@return')->getType());
``` 

#### Method getAnnotationsByName
usage

```php
    $res = new Loot\PhpDocReader\PhpDocReader('
/**
  * @param int $int
  * @param string $string
  */');

    var_dump($res->getAnnotationsByName('@param'));
``` 

#### Method getAnnotations
usage

```php
    $res = new Loot\PhpDocReader\PhpDocReader('
/**
  * @param int $int
  * @param string $string
  */');

    var_dump($res->getAnnotations());
``` 
