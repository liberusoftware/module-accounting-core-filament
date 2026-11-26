<?php

declare(strict_types=1);

use Filament\Panel;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Liberu\Accounting\CoreFilament\AccountingCoreFilamentPlugin;
use Liberu\Accounting\CoreFilament\AccountingCoreFilamentServiceProvider;
use Liberu\Accounting\CoreFilament\Resources\BookResource;
use Liberu\Accounting\CoreFilament\Resources\BookResource\Pages\ListBooks;
use Liberu\Accounting\CoreFilament\Resources\LegalEntityResource;
use Liberu\Accounting\CoreFilament\Resources\LegalEntityResource\Pages\CreateLegalEntity;
use Liberu\Accounting\CoreFilament\Resources\LegalEntityResource\Pages\EditLegalEntity;
use Liberu\Accounting\CoreFilament\Resources\LegalEntityResource\Pages\ListLegalEntities;

it('registers and configures accounting-core-filament filament resources', function (): void {
    $provider = $this->app->register(AccountingCoreFilamentServiceProvider::class, force: true);
    if (method_exists($provider, 'boot')) {
        $provider->boot();
    }
    expect($provider)->toBeInstanceOf(AccountingCoreFilamentServiceProvider::class);
    $plugin = AccountingCoreFilamentPlugin::make();
    $panel = Panel::make()->id('test-accounting-core-filament');
    $plugin->register($panel);
    $plugin->boot($panel);
    expect($plugin->getId())->toBeString()->not->toBeEmpty();
    $host = new ListBooks();
    expect(BookResource::getPages())->toBeArray();
    expect(BookResource::form(Schema::make($host)))->toBeInstanceOf(Schema::class);
    expect(BookResource::table(Table::make($host)))->toBeInstanceOf(Table::class);
    expect(LegalEntityResource::getPages())->toBeArray();
    expect(LegalEntityResource::form(Schema::make($host)))->toBeInstanceOf(Schema::class);
    expect(LegalEntityResource::table(Table::make($host)))->toBeInstanceOf(Table::class);
    $hdrActions = Closure::bind(fn () => $this->getHeaderActions(), new ListBooks(), ListBooks::class)();
    expect($hdrActions)->toBeArray();
    $hdrActions = Closure::bind(fn () => $this->getHeaderActions(), new ListLegalEntities(), ListLegalEntities::class)();
    expect($hdrActions)->toBeArray();
    expect(new CreateLegalEntity())->toBeInstanceOf(CreateLegalEntity::class);
    expect(new EditLegalEntity())->toBeInstanceOf(EditLegalEntity::class);
});
