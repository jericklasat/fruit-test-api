<h2>Initial Setup</h2>

Install docker your machine.\
Execute:
  - <code>cp .env.save .env</code> and make sure to update necessary credentials
  - <code>make-deploy</code>
  - <code>make migrate</code>

Note: If the pem files can't be access run <code>sudo chmod 775</code> (or 777)<code> -R config/jwt/</code>

<h2>Running the application</h2>
Execute: <code>make start</code>

Optionally if you want to see all the logs in the terminal you can use: <code>make watch</code>

<h2>Imports</h2>
To import fruits run: <code>make import-fruits type=all</code>
