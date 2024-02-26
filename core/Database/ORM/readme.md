# Введение
### Класс: Query

#### Свойства:
- private array $queryTables = []
- private string $query = ''
- private array $usedFunctions = []
- private array $usedColumns = []
- private array $usedRenaming = []

#### Методы:
1. __construct(string $query, string $table)
    - Конструктор для инициализации класса Query.
    - Параметры:
        - $query (string): Начальная строка запроса.
        - $table (string): Начальная таблица.

2. addRenameToList(string $column, string $name):void
    - Добавить переименование столбца (используется в случаях, когда использовалась функция ***as*** к примеру).
    - Параметры:
        - $column (string): Название столбца.
        - $name (string): Новое имя.

3. getUsedRenaming(): array
    - Получить список переименованных столбцов.

4. getQuery(): string
    - Получить текущую строку запроса.

5. setQuery($query): void
    - Установить новую строку запроса.

6. addToQuery($query): void
    - Добавить дополнение к существующему запросу.

7. getQueryTables(): array
    - Получить список таблиц, участвующих в запросе.

8. setQueryTables(array $queryTables): void
    - Установить список таблиц, участвующих в запросе.

9. addQueryTable(string $table):void
    - Добавить в список новую таблицу, участвующую в запросе.

10. getUsedFunctions(): array
    - Получить список использованных функций в запросе.
    Выведет применённые в запросе функции по типу ***SELECT*** / ***JOIN*** и т.д.

11. addUsedFunction(string $usedFunction): void
    - Добавить использованную функцию в запрос.

12. testQuery(): bool
    - Проверить запрос путем попытки его выполнения.

13. addUsedColumns(array|string $columns): void
    - Добавить в список столбцы, используемые в запросе.

14. getUsedColumns(): array
    - Получить список использованных в запросе столбцов.

15. __toString(): string
    - Строковое представление запроса.
    Эта функция вызовется автоматически, при попытке преобразовать
    объект класса Query в текст.

### Класс: QueryBuilder

#### Свойства:
- private Query $query

#### Методы:
1. `__construct(Query $query = new Query('', ''))`
    - Конструктор класса QueryBuilder.
    - Параметры:
        - $query (Query): Объект класса Query (по умолчанию создается пустой объект Query).

2. `obj->getQueryObject(): Query`
    - Получить объект запроса (Query), ассоциированный с QueryBuilder.

3. `obj->getQuery(): string`
    - Получить строку запроса из объекта Query.

4. `::getTableRestrictions(string $table): array`
    - Возвращает массив вида ['имя_колонки']=>['ограничения']
    - Ограничения включают в себя тип переменной в столбце, максимальное количество знаков, требуется ли заполнять эту колонку и содержит ли она надстройку `auto_increment`

5. `::isTableExists(string $table): bool`
    - Проверить, существует ли таблица с указанным именем.

6. `::getTableColumnsNames(string $table): array`
    - Получить названия столбцов для указанной таблицы.

7. `::isColumnExistInTable(string $columnName, string $table): bool`
    - Проверить, существует ли указанный столбец в таблице.

8. `::itemListHandler(string $items, string $table): string`
    - Обрабатывает список колонок, проверяет их наличие в таблице 
   и формирует их в строку для использования в запросе.

9. `::select(string $itemList, string $table, bool $blacklist = false): QueryBuilder`
    - Создать запрос на выборку данных из таблицы.
    - Параметры:
        - $itemList (string): Список столбцов для выборки. На вход принимает также варианты `all` и `*`.
          - all вернёт запрос вида ***SELECT 'все колонки в таблице перечислены' FROM table***
          - `*` вернёт запрос вида ***SELECT * FROM table***
        - $table (string): Имя таблицы.
        - $blacklist (bool): Флаг использования черного списка столбцов. При применении select отработает так, что выберет всё, кроме перечисленного
10. `obj->join(string $itemList, string $table, string $by = 'id', int $flag = INNER): self`
    - Присоединить таблицу к запросу.
    - Метод проверяет наличие указанных столбцов и формирует соответствующий SQL запрос для соединения.
    - Параметры:
        - $itemList (string): Список столбцов для соединения.
        - $table (string): Имя таблицы для присоединения.
        - $by (string): Ключ присоединения (по умолчанию 'id'. это значение вызовет присоединение по совпадению полей id в таблицах).
        - $flag (int): Тип соединения (по умолчанию INNER).
          - Типы соединения определяются по флагу:
            - INNER: Внутреннее соединение.
            - LEFT: Левое соединение.
            - RIGHT: Правое соединение.
            - FULL: Полное соединение.
            - CROSS: Кросс-соединение.
      - Возвращает экземпляр QueryBuilder для цепочного вызова методов.

11. `obj->where(string|QueryBuilder $condition, ?QueryBuilder $selectQuery = null, string $typeOfAddition = 'AND'): self`
    - Добавляет условие WHERE к текущему запросу.
    - Параметры:
        - $condition (string|QueryBuilder): Условие для оператора WHERE.
        - $selectQuery (?QueryBuilder): Дополнительный подзапрос для сравнения (по умолчанию null).
        - $typeOfAddition (string): Тип условия для добавления ('AND', 'OR', 'NOT') (по умолчанию 'AND').
    - Возвращает экземпляр класса QueryBuilder для цепочного вызова методов.

12. `obj->orderBy(string $condition, int $flag = ASCENDING): self`
    - Добавляет сортировку ORDER BY к текущему запросу.
    - Параметры:
        - $condition (string): Условие сортировки.
        - $flag (int): Направление сортировки (ASCENDING по умолчанию).
    - Возвращает экземпляр класса QueryBuilder для цепочного вызова методов.

13. `obj->refactorTableName(string $nameToChange, string $asName): self`
    - Заменяет имя таблицы в запросе на новое имя. То есть применяет alias к имени таблицы в запросе
    - Параметры:
        - $nameToChange (string): Имя таблицы для замены.
        - $asName (string): Новое имя таблицы.
    - Возвращает экземпляр класса QueryBuilder для цепочного вызова методов.

14. `obj->as(string|array $nameToApply, string|array $asName): self`
    - Применить функцию AS к столбцам в SQL запросе, чтобы изменить их названия.
    - Если параметры представлены как массивы и содержат одинаковое количество элементов, функция переименовывает соответствующие столбцы в запросе.
    - Параметры:
        - $nameToApply (string|array): Названия столбцов или массив названий столбцов, которые требуется переименовать.
        - $asName (string|array): Новые названия столбцов или массив новых названий столбцов.
    - Возвращает экземпляр QueryBuilder для цепочного вызова методов.

15. `::insert(string $table, array|string $column, array|string $value, array $validationRules = []): bool`
    - Выполняет операцию вставки данных в указанную таблицу базы данных.
    - Все передаваемые значения проходят проверки на `sql_escape_string` и на соответствие требованиям типам данных колонок таблицы, куда вставляются данные
    - Параметры:
        - $table (string): Имя таблицы, в которую выполняется вставка данных.
        - $column (array|string): Названия столбцов или одно название столбца для вставки данных.
        - $value (array|string): Значения для вставки в соответствующие столбцы.
        - $validationRules (array): Правила валидации (по умолчанию пустой массив), можно передать туда настройки валидации полей.
    - Возвращает true, если операция вставки выполнена успешно, в противном случае false.

16. `::update(string $table, array|string $column, array|string $newValue, array|string|int $updateConditions, array $validationRules = [])`
    - Выполняет обновление данных в указанной таблице базы данных в соответствии с указанными условиями.
    - Параметры:
        - $table (string): Имя таблицы, в которой выполняется обновление данных.
        - $column (array|string): Колонки для обновления или одна колонка, если обновление одиночного столбца.
        - $newValue (array|string): Новые значения для столбцов.
        - $updateConditions (array|string|int): Условия обновления данных если передаётся int, то условие будет `WHERE id = int`
        - $validationRules (array): Правила валидации (по умолчанию пустой массив), можно передать туда настройки валидации полей.
    - Возвращает true, если обновление данных выполнено успешно, в противном случае false.

17. `::aggregate(string $column, int $function = COUNT, ?string $as = null, ?string $groupBy = null): self`
    - Производит агрегацию данных по указанному столбцу в запросе.
    - Параметры:
        - $column (string): Название столбца для агрегации.
        - $function (int): Функция агрегации (по умолчанию COUNT).
            - Возможные значения:
                - AVERAGE: Вычисляет среднее значение (AVG).
                - SUM: Вычисляет сумму значений (SUM).
                - MIN: Находит минимальное значение (MIN).
                - MAX: Находит максимальное значение (MAX).
                - COUNT: Подсчитывает количество строк (COUNT).
        - $as (string, optional): Название, под которым будет возвращен результат агрегации.
        - $groupBy (string, optional): Поле для группировки результатов (по умолчанию по самому столбцу).
    - Возвращает экземпляр QueryBuilder для цепочного вызова методов.
### Примеры
Все примеры далее буду записывать через`->getQuery()`. Такие запросы можно напрямую отправлять в `DBHandler->getResult(полученный query)`
1. `QueryBuilder::select('id, title','item')->getQuery()`
   - Вернёт строку вида `SELECT item.id, item.title FROM item`
2. `QueryBuilder::select('id', 'color', true)->getQuery()`
   - Вернёт строку вида `SELECT color.name, color.engName FROM color` т.к. указан `blacklist=true`
3. `QueryBuilder::insert('color', 'name, engName', 'красненький, red')`
   - Запрос будет вида `INSERT INTO color(name, engName) VALUES ('красненький', 'red')`
     - Порядок ввода колонок не важен - он выставится в соответствии с порядком колонок в базе данных!
     - то есть `QueryBuilder::insert('color', 'name, engName', 'красненький, red')` и `QueryBuilder::insert('color', 'engName, name', 'красненький, red')` сделают одно и то же!
   - Внесёт данные в базу данных
     - Нельзя вводить несколько значений сразу (то есть как в примере сразу 2 цвета)
     - Нельзя получить строку запроса. Только true если запрос выполнился и false, если нет
     - Нельзя инсёртить поля со значением `auto_increment`!
4. `QueryBuilder::update('item', 'title, color_id', 'newName, 4', 4)`
   - Запрос будет вида `UPDATE item SET title 'newName' WHERE item.id = 4; UPDATE item SET color_id = 4 WHERE item.id = 4`
     - Для нескольких изменений как в примере будут несколько запросов.
     - Выполнение не произойдёт частично, только если успешно пройдёт тестирование ВСЕХ подзапросов. Иначе - false
   - Изменит данные для какого-то объекта в БД. По умолчанию, если в условия внести `int` то применится условие `WHERE item.id = int`
     - Нельзя вводить несколько значений таблиц сразу
     - Нельзя получить строку запроса. Только true если запрос выполнился и false, если хотя бы один из подзапросов нет
     - Нельзя менять поля со значением `auto_increment`!
# Цепной запрос. 
Для всех нестатичных запросов (кроме select) не предусмотрены цепные запросы. 
Однако с select - обычно начинаются все цепные запросы
1. `QueryBuilder::select('id','item')->join('name', 'manufacturer')->where('item.id > 10')-getQuery()`
   - Этот запрос даст следующий результат: `SELECT item.id FROM item INNER JOIN manufacturer on manufacturer.id = item.manufacturer_id WHERE item.id > 5`
   - В блоке where необходимо чётко прописывать `condition` используя полное название колонки, то есть `item.id` вместо просто id.

