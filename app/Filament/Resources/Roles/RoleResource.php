<?php

namespace App\Filament\Resources\Roles;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleResource extends Resource
{
    // 👇 tell Filament to use Spatie’s model directly
    protected static ?string $model = Role::class;

    protected static string|null|\BackedEnum $navigationIcon  = Heroicon::OutlinedShieldCheck;
    protected static string|null|\UnitEnum $navigationGroup = 'User Management';

    public static function form(Forms\Form|\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Role Name')
                ->required()
                ->unique(ignoreRecord: true),

            MultiSelect::make('permissions')
                ->label('Permissions')
                ->relationship('permissions', 'name')
                ->preload(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('permissions.name')
                    ->label('Permissions')
                    ->badge()
                    ->separator(', ')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
