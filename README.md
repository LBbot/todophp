# todophp

This learning project was designed to be run on a local instance of both PHP and CouchDB. I have included some simple instructions that I personally used, they could help you get it up and running but they are not compulsory, more of a note-to-self for me. 

There are some Composer dependencies to set up the Guzzle client. A Vendor folder is needed. "composer update" or "composer install" will probably do this, but this needs to be verified.

Ran the following in the terminal:
```bash
php -S localhost:8000
```

Pointed browser to: http://localhost:8000/index.php

Now it just needs CouchDB running (on Mac can be done simply opening "Apache CouchDB.app" in Finder), which I set up on http://127.0.0.1:5984 (but you can obviously edit this in CONFIG.PHP) with a database titled to-do-list. It must include "_design/tickcheck", which can be done with Create Document and pasting the following views and information:

```javascript
{
  "_id": "_design/tickcheck",
  "views": {
    "tickfalse": {
      "map": "function (doc) {\n  if (doc.ticked==false) {\n\t\temit ([doc.created_at, doc.note, doc.readable_date], doc)\n\t}\n}"
    },
    "tickbydate": {
      "map": "function (doc) {\n  if (doc.ticked==true) {\n\t\temit ([doc.created_at, doc.note, doc.readable_date], doc)\n\t}\n}"
    }
  },
  "language": "javascript"
}
```
 (Reminder: a _rev unique ID will be automatically created.)

## Known issues
* Timezone is UTC, so does not reflect local time, or British Summer Time.
* No login or security (aside from input validation/sanitisation) so it can only really be run locally.
