Commands
========

Find or clean-up Medias Entities unlinked to physical Media files
-----------------------------------------------------------------

use the command below:

```sh
$ ./app/console tms-media:cleanup:without-file-medias [-f|--force]
```

use -f or --force to erase media entites found (whose are unlinked to any file).


Find or clean-up physical Media Files unlinked to Media Entities
----------------------------------------------------------------

use the command below:
```sh
$ ./app/console tms-media:cleanup:orphan-files [-f|--force] [--em="..."] folderPath
```

where folderPath is the path of your bucket.
use -f or --force to erase files unlinked to media entites.

