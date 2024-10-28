# Processo di Reset della Password

### Spiegazione Sintetica del Backend
Il codice backend gestisce due funzioni principali per il processo di reset della password, fornendo endpoint e logica per il corretto funzionamento.

#### 1. Invio della Richiesta di Reset (`initResetPassword`)
- **Endpoint**: `POST /api/init_reset_pwd`
- **Parametri**: L’utente invia un’email (`email`) tramite una richiesta POST.
- **Funzionalità**:
  - Verifica l’esistenza dell’utente; in caso contrario, restituisce un errore.
  - Genera e memorizza un token per il reset della password nel database.
  - Disabilita temporaneamente l’account dell’utente.
  - Invia un’email contenente un link (richiesta GET) con il token di reset e l'email.
- **Risultato**: Una risposta di successo conferma l'invio del link per il reset all'email dell’utente.

#### 2. Esecuzione del Reset Password (`resetPassword`)
- **Endpoint**: `GET /api/reset_pwd`
- **Parametri**: Richiesta POST con `token`, `email`, `new_password`, `conf_new_password`.
- **Funzionalità**:
  - Verifica la validità del token e l’esistenza dell’utente.
  - Confronta le nuove password per garantirne la corrispondenza.
  - Cripta la nuova password e aggiorna il database.
  - Rimuove il token di reset dal database.
- **Note**:
  - Secondo Nicola il token e l'email dovresti ottenerli attraverso il javascript
- **Risultato**: Se tutti i controlli sono superati, viene restituita una risposta di successo.

### Implementazione Frontend
L'idea del funzionamento.

#### 1. Pagina di Invio della Richiesta di Reset Password
- Contiene un form per l’input dell’email.
- Include un pulsante per inviare la richiesta, che chiama il metodo `initResetPassword`.
- Gestisce e visualizza messaggi di successo o errore in base alla risposta ricevuta.

#### 2. Pagina di Reset Password
- Presenta un form con input per `new_password`, e `conf_new_password`.
- Devono essere presenti come parametri hidden `token` e `email`.
- Include un pulsante per confermare il reset, che chiama il metodo `resetPassword`.
- Visualizza messaggi di errore (ad esempio, mismatch delle password) o conferma di successo.

## Note per il linking tra Frontend e Backend
Per impostare i link inviati nelle email basta settare gli appositi campi della classe UserController:
- $this->$psw_rst_page_url= 'https://localhost/api/reset_pwd';
- $this->$register_page_url= 'https://localhost/api/verify_user';