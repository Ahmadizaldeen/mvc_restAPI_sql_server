set PROJECT="php_mvc_rest_sql"
mkdir "%PROJECT%"
cd "%PROJECT%"

mkdir app data config include public docs css js test

cd app
mkdir models views controllers helpers 

echo Projektstruktur erstellt: %PROJECT%