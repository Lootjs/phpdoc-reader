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
Получить первую аннотацию по названию.

```php
$res = new Loot\PhpDocReader\PhpDocReader('
/**
  * @return int
  */');

var_dump($res->getAnnotation('@return')->getType());
``` 

#### Method getAnnotationsByName
Получить все аннотации по названию.

```php
$res = new Loot\PhpDocReader\PhpDocReader('
/**
  * @param int $int
  * @param string $string
  */');

var_dump($res->getAnnotationsByName('@param'));
``` 

#### Method getAnnotations
Получить первую аннотацию.

```php
$res = new Loot\PhpDocReader\PhpDocReader('
/**
  * @param int $int
  * @param string $string
  */');

var_dump($res->getAnnotations());
``` 

### Class PhpDocLine
#### Method getName()
Возвращает название аннотации.

#### Method getType()
Возвращает тип аннотации.

#### Method getDescription()
Возвращает описание аннотации.

#### Method getVariable()
Возвращает переменную в аннотации.
