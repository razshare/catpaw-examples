<?php
use CatPaw\Core\Attributes\Entry;
use CatPaw\Core\Attributes\Service;
use function CatPaw\Core\stop;
use function CatPaw\Core\uuid;
use const CatPaw\Web\__APPLICATION_JSON;
use const CatPaw\Web\__OK;
use const CatPaw\Web\__TEXT_PLAIN;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\ProducesPage;
use CatPaw\Web\Attributes\Summary;
use CatPaw\Web\Page;
use CatPaw\Web\Server;
use function CatPaw\Web\success;

class Account {
    function __construct(
        public string $username,
        public string $name,
    ) {
    }
}

#[Service]
class AccountService {
    /** @var array<Account> */
    private array $accounts = [];

    #[Entry] function start() {
        $this->accounts[] = new Account(username: uuid(), name: 'Raz');
        $this->accounts[] = new Account(username: uuid(), name: 'Marta');
        $this->accounts[] = new Account(username: uuid(), name: 'Tom');
        $this->accounts[] = new Account(username: uuid(), name: 'Electra');
        $this->accounts[] = new Account(username: uuid(), name: 'Brahim');
        $this->accounts[] = new Account(username: uuid(), name: 'Oscar');
    }

    /**
     * Find accounts by name.
     * @param  Page           $page
     * @param  string         $name
     * @return array<Account>
     */
    function findByName(Page $page, string $name):array {
        $result = array_filter($this->accounts, fn (Account $account) => strtolower($account->name) === strtolower($name));
        return array_slice($result, $page->start, $page->size);
    }

    /**
     * Find all accounts (paginated).
     * @param  Page  $page
     * @return array
     */
    function findAll(Page $page):array {
        return array_slice($this->accounts, $page->start, $page->size);
    }
}

/**
 *
 * @param AccountService $accountService
 * @param string         $name
 */
#[Summary('Find accounts by their name.')]
#[ProducesPage(
    status: __OK,
    contentType: __APPLICATION_JSON,
    description: 'on success',
    className: Account::class,
    example: new Account(username:'b5e6a138-0d9e-42d4-aa2c-db33a4fcec37', name:'user1')
)]
function findAccountsByName(AccountService $accountService, Page $page, string $name) {
    $items = $accountService->findByName($page, $name);
    return success($items)->as(__APPLICATION_JSON)->page($page);
}

#[Summary('Toggle an account, activating or deactivating it.')]
#[Produces(
    status: __OK,
    contentType: __TEXT_PLAIN,
    description: 'on success',
    className: 'string',
    example: 'Account user1 has been activated.'
)]
function toggleAccountByUsername(string $username, bool $active) {
    if ($active) {
        return success("Account $username has been activated.");
    }
    return success("Account $username has been deactivated.");
}

#[Summary('Find all accounts.')]
#[ProducesPage(
    status: __OK,
    contentType: __APPLICATION_JSON,
    description: 'on success',
    className: Account::class,
    example: new Account(username:'b5e6a138-0d9e-42d4-aa2c-db33a4fcec37', name:'user1')
)]
function findAll(AccountService $accountService, Page $page) {
    return success($accountService->findAll($page))->as(__APPLICATION_JSON)->page($page);
}

function main(): void {
    $server = Server::create( www:'./public' )->try($error)                                                or stop($error);
    $server->router->get('/account/by-name/{name}', findAccountsByName(...))->try($error)                  or stop($error);
    $server->router->get('/account/{username}/toggle/{active}', toggleAccountByUsername(...))->try($error) or stop($error);
    $server->router->get('/accounts', findAll(...))->try($error)                                           or stop($error);
    showSwaggerUI($server)->try($error)                                                                    or stop($error);
    $server->start()->await()->try($error)                                                                 or stop($error);
}
