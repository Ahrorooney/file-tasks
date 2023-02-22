<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <h1 align="center">File Tasks</h1>
    <br>
</p>

### Instructions for installing

1. Clone the project

2. Composer install

3. Enter database configurations in config/db.php file

4. Commands:
- php yii migrate --migrationPath=@yii/rbac/migrations
- php yii migrate

5. 5 users (with related roles) and admin, user, moderator roles will be generated when migrated:
-username: admin; password: password
-username: user; password: password
-username: user_2; password: password
-username: moderator; password: password
-username: moderator_2; password: password


6. Links (x here variable). Postman collection will be shared in telegram:
- http://localhost:8080/auth/login
- http://localhost:8080/file/create
- http://localhost:8080/file
- http://localhost:8080/file/delete?id=x
- http://localhost:8080/file/view?id=x
- http://localhost:8080/file/download?id=x

7. Bearer token is used instead of JWT
