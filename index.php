<?php
declare(strict_types=1);

// Подключение всех классов
require_once 'Transaction.php';
require_once 'TransactionStorageInterface.php';
require_once 'TransactionRepository.php';
require_once 'TransactionManager.php';
require_once 'TransactionTableRenderer.php';

// --- Создание начальных данных (не менее 10 транзакций) ---
$repository = new TransactionRepository();

// Вспомогательная функция для генерации случайной даты в пределах последних 30 дней
function randomDate(): DateTime {
    $daysAgo = rand(0, 30);
    return (new DateTime())->sub(new DateInterval('P' . $daysAgo . 'D'));
}

// Массив с данными транзакций: сумма, описание, получатель
$transactionsData = [
    [1500.00, 'Покупка продуктов', 'Магазин Пятёрочка'],
    [350.00, 'Обед в кафе', 'Кафе Уют'],
    [89.50, 'Проезд', 'Транспортная карта'],
    [2300.00, 'Аренда жилья', 'Арендатор'],
    [450.00, 'Заправка', 'АЗС Лукойл'],
    [1250.00, 'Покупка техники', 'Магазин Электроника'],
    [75.00, 'Кофе', 'Кофейня Старбакс'],
    [600.00, 'Такси', 'Яндекс.Такси'],
    [200.00, 'Подписка', 'Netflix'],
    [999.99, 'Книги', 'Лабиринт'],
];

$id = 1;
foreach ($transactionsData as $data) {
    [$amount, $description, $merchant] = $data;
    $date = randomDate();
    $transaction = new Transaction($id++, $date, $amount, $description, $merchant);
    $repository->addTransaction($transaction);
}

// --- Создание объектов для работы ---
$manager = new TransactionManager($repository);
$renderer = new TransactionTableRenderer();

// --- Вывод результатов ---

// 1. Все транзакции
echo '<h2>Все транзакции</h2>';
echo $renderer->render($repository->getAllTransactions());

// 2. Общая сумма
echo '<h2>Общая сумма: ' . number_format($manager->calculateTotalAmount(), 2) . '</h2>';

// 3. Сумма за последние 10 дней
$start = (new DateTime())->sub(new DateInterval('P10D'))->format('Y-m-d');
$end = (new DateTime())->format('Y-m-d');
echo '<h2>Сумма за период с ' . $start . ' по ' . $end . ': '
    . number_format($manager->calculateTotalAmountByDateRange($start, $end), 2) . '</h2>';

// 4. Количество транзакций для конкретного получателя
$merchant = 'Магазин Пятёрочка';
$count = $manager->countTransactionsByMerchant($merchant);
echo "<h2>Количество транзакций для \"$merchant\": $count</h2>";

// 5. Сортировка по дате (от старых к новым)
echo '<h2>Транзакции, отсортированные по дате (от старых к новым)</h2>';
echo $renderer->render($manager->sortTransactionsByDate());

// 6. Сортировка по сумме (по убыванию)
echo '<h2>Транзакции, отсортированные по сумме (убывание)</h2>';
echo $renderer->render($manager->sortTransactionsByAmountDesc());

// 7. Дополнительно: удаление одной транзакции и показ обновлённой таблицы
$repository->removeTransactionById(3); // удаляем транзакцию с id=3 (проезд)
echo '<h2>После удаления транзакции с ID=3</h2>';
echo $renderer->render($repository->getAllTransactions());