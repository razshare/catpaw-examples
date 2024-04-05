<?php
use function CatPaw\Core\anyError;
use CatPaw\Core\Attributes\Entry;
use CatPaw\Core\Attributes\Service;

use CatPaw\Core\Unsafe;
use function CatPaw\Core\uuid;
use const CatPaw\Web\APPLICATION_JSON;
use CatPaw\Web\Attributes\Produces;
use CatPaw\Web\Attributes\ProducesPage;
use CatPaw\Web\Attributes\Summary;
use const CatPaw\Web\OK;
use CatPaw\Web\Page;
use CatPaw\Web\Server;
use function CatPaw\Web\success;
use const CatPaw\Web\TEXT_PLAIN;

class Account {
    function __construct(
        public string $id,
        public string $name,
    ) {
    }
}

#[Service]
class AccountService {
    /** @var array<Account> */
    private array $accounts = [];

    #[Entry] function start() {
        $this->accounts[] = new Account(id: uuid(), name: 'Raz');
        $this->accounts[] = new Account(id: uuid(), name: 'Marta');
        $this->accounts[] = new Account(id: uuid(), name: 'Tom');
        $this->accounts[] = new Account(id: uuid(), name: 'Electra');
        $this->accounts[] = new Account(id: uuid(), name: 'Brahim');
        $this->accounts[] = new Account(id: uuid(), name: 'Oscar');
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
    status: OK,
    contentType: APPLICATION_JSON,
    description: 'On success',
    className: Account::class,
    example: new Account(id:'b5e6a138-0d9e-42d4-aa2c-db33a4fcec37', name:'user1')
)]
function findAccountsByName(AccountService $accountService, Page $page, string $name) {
    $items = $accountService->findByName($page, $name);
    return success($items)->as(APPLICATION_JSON)->page($page);
}

#[Summary('Toggle an account, activating or deactivating it.')]
#[Produces(
    status: OK,
    contentType: TEXT_PLAIN,
    description: 'On success',
    className: 'string',
    example: 'Account user1 has been activated.'
)]
function toggleAccountById(string $id, bool $active) {
    if ($active) {
        return success("Account $id has been activated.");
    }
    return success("Account $id has been deactivated.");
}

#[Summary('Find all accounts.')]
#[ProducesPage(
    status: OK,
    contentType: APPLICATION_JSON,
    description: 'On success',
    className: Account::class,
    example: new Account(id:'b5e6a138-0d9e-42d4-aa2c-db33a4fcec37', name:'user1')
)]
function findAll(AccountService $accountService, Page $page) {
    return success($accountService->findAll($page))->as(APPLICATION_JSON)->page($page);
}

function main(): Unsafe {
    return anyError(function() {
        $server = Server::create(www:'./public')->try($error)
        or yield $error;
        
        $server->router->get('/account/by-name/{name}', findAccountsByName(...))->try($error)
        or yield $error;
        
        $server->router->get('/account/{id}/toggle/{active}', toggleAccountById(...))->try($error)
        or yield $error;
        
        $server->router->get('/accounts', findAll(...))->try($error)
        or yield $error;
        
        showSwaggerUI($server)->try($error)
        or yield $error;
        
        $server->start()->try($error)
        or yield $error;
    });
}
