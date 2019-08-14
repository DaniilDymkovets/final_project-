<a href="//www.free-kassa.ru/"><img src="//www.free-kassa.ru/img/fk_btn/14.png"></a>
**Start project**

**Clone project **
`git clone https://Divotek@bitbucket.org/Divotek/hipe.git`

rename `.env-example` to `.env`


Настроить соединение со своей Базой данных
Далее выполнить последовательно

`composer update`

`php artisan key:generate `


---------------------------
Запуск миграций
`php artisan migrate`

Установка первоначальных данных
`php artisan db:seed `

или одной командой
`php artisan migrate:refresh --seed`


---------------------------
По умолчанию сохдаются следующие пользователей
---------------------------

**Users**
1й
подписал второго
`user@hope.local` / `user@hope.local`

2й
подписан под первым, подписал третьего
`User_two@hope.local` / `User_two@hope.local`

3й
подписан под вторым
`User_tree@hope.local` / `User_tree@hope.local`


**Admins**

Super
`admin@hope.local` / `admin@hope.local`

Manager
`manager@hope.local` / `manager@hope.local`

---------------------------
Консольные команды
---------------------------
Начисляет проценты на все активные депозиты, на текущий баланс.
--test отменит правило начисления процентов по дням и месяцам.
`php artisan updateDepositProcent --test`




На рабочем сервере настроит CRON
`* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1`