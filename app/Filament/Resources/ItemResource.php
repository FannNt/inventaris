<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use App\Models\Ruangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('merk')->required(),
                Forms\Components\Select::make('kondisi')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak' => 'Rusak',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->required(fn($get) => $get('kondisi') === 'Rusak')
                    ->visible(fn($get) => $get('kondisi') === 'Rusak'),
                Forms\Components\Select::make('id_ruangan')->
                    options(function (){
                        return Ruangan::all()->pluck('nama', 'id');
                })->label('Ruangan')
                ->required(),
                Forms\Components\Select::make('tahun_pengadaan')
                    ->options(
                        collect(range(now()->year, now()->year - 30))
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray()
                    )
                    ->label('Year')
                    ->required(),
                Forms\Components\DatePicker::make('masa_berlaku')
                ->label('Masa Berlaku')
                ->displayFormat('d/m/Y')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('merk'),
                Tables\Columns\TextColumn::make('kondisi'),
                Tables\Columns\TextColumn::make('masa_berlaku'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_ruangan')
                    ->label('Ruangan')
                    ->options(function (){
                        return Ruangan::all()->pluck('nama', 'id');
                    }),
                Tables\Filters\TernaryFilter::make('kondisi')
                ->trueLabel('Baik')
                    ->falseLabel('Rusak'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
