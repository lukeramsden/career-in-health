## Career in Health (project care)

### Installation
#### Dependencies
- PHP 7.0
- NPM
- MariaDB

```bash
npm install
npm run prod
composer install
php artisan symlink
cp .env.example .env
vim .env # config to match environment
php artisan migrate
cp vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64 /usr/local/bin/ # maybe sudo
chmod +x /usr/local/bin/wkhtmltopdf-amd64                              # maybe sudo
```


### General Ideas
- Post Jobs (care home)
- Find Jobs (person)
- CV Builder
- Personnel file generator
- Care home employment history
- Stripe for payment
- On new job upload, look for persons that can do job
- On adding job references look to see if it is in the system, if not email / sms them?
- Facebook / Google / Twitter logins (maybe?)

### Potential Competitors
https://jobs.communitycare.co.uk/?intcmp=Jobs-Navbar
https://carerecruiter.co.uk/ DA - 18, Found by adwords

### Potential Media Boost
https://ukrecruiter.co.uk/ DA - 42
https://www.socialtalent.com DA - 32 Maybe..
