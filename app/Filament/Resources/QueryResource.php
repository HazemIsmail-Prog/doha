<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueryResource\Pages;
use App\Filament\Resources\QueryResource\RelationManagers;
use App\Models\Query;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QueryResource extends Resource
{
    protected static ?string $model = Query::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('bidder_id')
                    ->hidden(auth()->user()->type == 'bidder')
                    ->label('Bidder')
                    ->disabled(auth()->user()->type != 'admin')
                    ->options(User::where('type', 'bidder')->pluck('name', 'id'))
                    ->default(3),
                Select::make('consultant_id')
                    ->hidden(auth()->user()->type == 'bidder')
                    ->label('Consultant')
                    ->options(User::where('type', 'consultant')->pluck('name', 'id'))
                    ->disabled(auth()->user()->type != 'admin'),
                TextInput::make('discipline')
                    ->disabled(auth()->user()->type != 'admin'),
                TextInput::make('particulars')
                    ->disabled(auth()->user()->type != 'admin'),
                TextInput::make('volume_number')
                    ->disabled(auth()->user()->type != 'admin'),
                TextInput::make('page_clause_number')
                    ->disabled(auth()->user()->type != 'admin'),
                RichEditor::make('description')
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'orderedList',
                        'underline',
                    ])
                    ->columnSpanFull()
                    ->disabled(auth()->user()->type != 'admin'),
                RichEditor::make('clarification')
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'orderedList',
                        'underline',
                    ])
                    ->columnSpanFull()
                    ->disabled(auth()->user()->type != 'admin'),
                RichEditor::make('reply')
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'orderedList',
                        'underline',
                    ])
                    ->columnSpanFull()
                    ->disabled(auth()->user()->type == 'bidder'),

                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Query::query()
                    ->when(auth()->user()->type == 'consultant', function ($q) {
                        $q->where('consultant_id', auth()->id());
                    })
                    ->when(auth()->user()->type == 'bidder', function ($q) {
                        $q->where('bidder_id', auth()->id());
                    })
            )
            ->columns([
                TextColumn::make('bidder.name')
                    ->wrap(true)
                    ->searchable()
                    ->hidden(auth()->user()->type == 'bidder'),
                TextColumn::make('discipline')
                    ->searchable()
                    ->wrap(true),
                TextColumn::make('particulars')
                    ->searchable()
                    ->wrap(true),
                TextColumn::make('volume_number')
                    ->searchable()
                    ->wrap(true),
                TextColumn::make('page_clause_number')
                    ->searchable()
                    ->wrap(true),
                TextColumn::make('description')
                    ->searchable()
                    ->markdown()
                    ->wrap(true),
                TextColumn::make('clarification')
                    ->searchable()
                    ->markdown()
                    ->wrap(true),
                TextColumn::make('reply')
                    ->searchable()
                    ->markdown()
                    ->wrap(true),
            ])
            ->filters([
                TernaryFilter::make('reply')
                    ->label('Reply Filter')
                    ->placeholder('All')
                    ->trueLabel('Replied')
                    ->falseLabel('Not Replied')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('reply'),
                        false: fn (Builder $query) => $query->WhereNull('reply'),
                        blank: fn (Builder $query) => $query,
                    )
                    ], layout: FiltersLayout::Dropdown)
            ->actions([
                Tables\Actions\EditAction::make()->slideOver()->hidden(auth()->user()->type == 'bidder'),
                Tables\Actions\ViewAction::make()->slideOver()->hidden(auth()->user()->type == 'consultant'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListQueries::route('/'),
            'create' => Pages\CreateQuery::route('/create'),
            // 'edit' => Pages\EditQuery::route('/{record}/edit'),
        ];
    }
}
