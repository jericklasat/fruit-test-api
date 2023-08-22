<h2>Initial Setup</h2>

Install docker your machine.\
Execute:
  - <code>make-deploy</code>
  - The clear cache will output an error due to incomplete credentials like the smtp, please update necessary .env and restart the container 
  - <code>make migrate</code>

Note: If the pem files can't be access run <code>sudo chmod 775</code> (or 777)<code> -R config/jwt/</code>

<h2>Running the application</h2>
Execute: <code>make start</code>

Optionally if you want to see all the logs in the terminal you can use: <code>make watch</code>

<h2>Imports</h2>
To import fruits run: <code>make import-fruits type=all</code>

<h2>Testing</h2>
Execute:

  - All test: <code>make codecept</code>
  - All in specific folder test(ex:): <code>make codecept tests/unit/fruit</code>
  - Specific file test(ex:): <code>make codecept tests/unit/fruit/FruitTest.php</code>