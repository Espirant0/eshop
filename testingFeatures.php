<?php

const ROOT = __DIR__;

require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/config/config.php';
require_once ROOT . '/routes.php';
use App\Service\DBHandler;

/*
Далее будут описаны методы и способы работы с БД через DBHandler
1. Инициализация.
Теперь не надо инициализировать коннект и проверять его, всё делается автоматом в DBHandler и также автоматом кидает ошибку если что
Теперь для коннекта достаточно прописать $var_name = new DBHandler();
и дальше работать непосредственно с переменной
2. Запросы к БД (в дальнейшем объяснении принимаем следующий факт: $DBOperator = new DBHandler();)
Теперь запросы выполняются следующим образом: $DBOperator->query('строка запроса с вашими селектами и тд')
То есть если я хочу вывести всё из таблицы миграций, я напишу $migrations = $DBOperator->query('SELECT * FROM migration')
В результате этого действия мы получим переменную $migrations вида \mysqli_result,
которую можно использовать для while($row = mysqli_fetch_assoc($migration)) и так далее.
В DBHandler также реализованы 2 дополнительных метода:
Статический $DBOperator->getResultStatic('строка запроса к бд')
И динамический $DBOperator->getResult('Строка запроса к бд')
Статический метод не требует создания объекта класса DBHandler и его можно вызвать откуда угодно
Динамический метод требует создания элемента класса.
Разницы между ними в выводимой информации нет совсем: они оба вернут массив массивов строк. Яснее будет на примере :)
3. Работа с БД
Так как DBHandler расширяет \mysqli то там можно использовать все методы родителя. Опишу некоторые моменты:
$DBOperator->query('запрос') - применит запрос к бд, как если бы вы писали скрипт в sql файле
Например: $DBOperator->query('ALTER TABLE manufacturer add column town') - создаст новое поле.
Притом результат этого запроса сохранять НЕ ОБЯЗАТЕЛЬНО
$DBOperator->query('запрос') - вернёт mysqli_result как мы помним, потому к этой всей штуке можно применять методы и mysqli_result!
пример: $DBOperator->query('запрос')->num_rows - выведет количество строк результата :)
Если же хочется как-то обрабатывать результат обращения к бд, то я советую, всё же делать так:
$var_array = $DBOperator->getResult('запрос')
foreach ($var_array as $var)
{
	//some work with $var
}
либо так(старый вариант):
$var_array = $DBOperator->getResult('запрос')
while($row = mysqli_fetch_assoc($var_array))
{
	//some work with $row
}
Системы идентичны, а дальше - дело вкуса.
Далее будет пример кода
*/
echo "-----Пример через foreach и DBO->getResult()!-----\n";
$DBOperator = new DBHandler();
$migrations = $DBOperator->getResult('SELECT * FROM migration');
foreach ($migrations as $migration)
{
	echo "migration {$migration['id']} has name {$migration['name']}\n";
}
#Как видно их примера выше суть такая же, как с вайлом, но на мой взгляд этот вариант удобнее :)
echo "-----Пример через while и DBO->query()!-----\n";
#Ниже приведу подобный пример для вайла:
$migrations = $DBOperator->query('SELECT * FROM migration');
while ($row = mysqli_fetch_assoc($migrations))
{
	echo "migration {$row['id']} has name {$row['name']}\n";
}
#Ну и на затравочку приведу такой же пример, но со статичным методом
echo "-----Пример статичного метода!-----\n";
$migrations = DBHandler::getResultStatic('SELECT * FROM migration');
foreach ($migrations as $migration)
{
	echo "migration {$migration['id']} has name {$migration['name']}\n";
}
#Этот файл никуда не подключён, собственно здесь можно спокойно проводить тесты :)
#Напоследок скажу, что ещё остаётся под вопросом unset для $DBoperator'а,
#потому что я точно не знаю, нужно ли это делать в конце каждой истории или нет, или стоит создать его 1 раз при подключении application...
#Попробуй далее написать по новой схеме через foreach запрос к бд, выводящий подобным образом Название цвета и его ID из БД-шки :) ENJOY!
$DBOperator = new DBHandler();
$result = $DBOperator->query('SHOW TABLES');
while ($row = mysqli_fetch_assoc($result))
{
	var_dump($row['Tables_in_eshop']);
}
\App\Service\ClearTestData::clear();
\App\Cache\FileCache::deleteAllCache();