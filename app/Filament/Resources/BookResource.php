<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Book;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use App\Filament\Exports\BookExporter;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Resources\BookResource\Pages;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookResource\RelationManagers;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Infolists\Components\MoneyEntry;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),

                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('genre')
                                    ->maxLength(255)
                                    ->required(),

                                TextInput::make('isbn')
                                    ->maxLength(255)
                                    ->required(),

                                TextInput::make('price')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('9.99')
                                    ->helperText('Price in euros (€) e.g. 9.99')
                                    ->suffixIcon('heroicon-o-currency-euro')
                                    ->suffixIconColor(Color::Green)
                                    ->required(),
                            ]),

                        Textarea::make('description')
                            ->rows(9)
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title'),
                TextEntry::make('isbn'),
                TextEntry::make('description'),
                TextEntry::make('genre')
                    ->badge(),
                MoneyEntry::make('price')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->description( fn( Book $book ) => str($book->description)->limit(50) )
                    ->searchable()
                    ->sortable(),
                TextColumn::make('genre')
                    ->badge(),
                TextColumn::make('price')
                    ->money(
                        currency: 'EUR',
                        locale  : 'fr_FR'
                    )
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(BookExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(BookExporter::class)
                        ->formats([
                            ExportFormat::Csv,
                        ]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
