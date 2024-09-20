<?php

namespace App\Filament\Resources;

use App\Filament\Exports\BookExporter;
use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookResource extends Resource
{
    protected static ?string $model           = Book::class;
    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Resources';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form( Form $form ): Form
    {
        return $form
            ->schema( [
                TextInput::make( 'title' )
                    ->maxLength( 255 )
                    ->required()
                    ->columnSpanFull(),

                Grid::make( 3 )
                    ->schema( [
                        Grid::make( 1 )
                            ->columnSpan( 1 )
                            ->schema( [
                                TextInput::make( 'genre' )
                                    ->maxLength( 255 )
                                    ->required(),

                                TextInput::make( 'isbn' )
                                    ->maxLength( 255 )
                                    ->required(),

                                TextInput::make( 'price' )
                                    ->numeric()
                                    ->step( 0.01 )
                                    ->placeholder( '9.99' )
                                    ->helperText( 'Price in euros (€) e.g. 9.99' )
                                    ->suffixIcon( 'heroicon-o-currency-euro' )
                                    ->suffixIconColor( Color::Green )
                                    ->required(),
                            ] ),

                        Textarea::make( 'description' )
                            ->rows( 9 )
                            ->required()
                            ->columnSpan( 2 ),
                    ] )
                    ->columnSpanFull(),

                Select::make( 'authors' )
                    ->multiple()
                    ->relationship( titleAttribute: 'name' )
                    ->required()
                    ->columnSpanFull(),
            ] );
    }

    public static function infolist( Infolist $infolist ): Infolist
    {
        return $infolist
            ->schema( [
                TextEntry::make( 'title' ),
                TextEntry::make( 'isbn' )
                    ->badge()
                    ->color( Color::Blue ),
                TextEntry::make( 'description' ),
                TextEntry::make( 'genre' )
                    ->badge(),
                TextEntry::make( 'price' )
                    ->money(
                        currency: 'EUR',
                        locale  : 'fr_FR'
                    ),
                TextEntry::make( 'authors.name' )
                    ->badge()
                    ->columnSpanFull(),
            ] );
    }

    public static function table( Table $table ): Table
    {
        return $table
            ->columns( [
                TextColumn::make( 'title' )
                    ->description( fn ( Book $book ) => str( $book->description )->limit( 50 ) )
                    ->searchable()
                    ->sortable(),
                TextColumn::make( 'genre' )
                    ->badge(),
                TextColumn::make( 'price' )
                    ->money(
                        currency: 'EUR',
                        locale  : 'fr_FR'
                    )
                    ->sortable(),
                TextColumn::make( 'comments_count' )
                    ->label( 'Comments' )
                    ->counts( 'comments' )
                    ->sortable(),
                TextColumn::make( 'authors_count' )
                    ->counts( 'authors' )
                    ->label( 'Authors' )
                    ->sortable(),
            ] )
            ->filters( [
                Filter::make( 'price' )
                    ->form( [
                        TextInput::make( 'from' )
                            ->label( 'Price From' )
                            ->numeric()
                            ->step( 0.01 )
                            ->placeholder( '9.99' )
                            ->helperText( 'Price in euros (€) e.g. 9.99' )
                            ->suffixIcon( 'heroicon-o-currency-euro' )
                            ->suffixIconColor( Color::Green )
                            ->debounce(),
                        TextInput::make( 'to' )
                            ->label( 'Price To' )
                            ->numeric()
                            ->step( 0.01 )
                            ->placeholder( '9.99' )
                            ->helperText( 'Price in euros (€) e.g. 9.99' )
                            ->suffixIcon( 'heroicon-o-currency-euro' )
                            ->suffixIconColor( Color::Green )
                            ->debounce(),
                    ] )
                    ->query( function ( Builder $query, array $data ) {
                        return $query
                            ->when( $data['from'], function ( Builder $query, ?string $from ) {
                                return $query->where( 'price', '>=', (float) $from * 100 );
                            } )
                            ->when( $data['to'], function ( Builder $query, ?string $to ) {
                                return $query->where( 'price', '<=', (float) $to * 100 );
                            } );
                    } ),
            ] )
            ->headerActions( [
                ExportAction::make()
                    ->exporter( BookExporter::class )
                    ->formats( [
                        ExportFormat::Csv,
                    ] ),
            ] )
            ->actions( [
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ] )
            ->bulkActions( [
                Tables\Actions\BulkActionGroup::make( [
                    ExportBulkAction::make()
                        ->exporter( BookExporter::class )
                        ->formats( [
                            ExportFormat::Csv,
                        ] ),
                    Tables\Actions\DeleteBulkAction::make(),
                ] ),
            ] );
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
            'index'  => Pages\ListBooks::route( '/' ),
            'create' => Pages\CreateBook::route( '/create' ),
            'edit'   => Pages\EditBook::route( '/{record}/edit' ),
        ];
    }
}
