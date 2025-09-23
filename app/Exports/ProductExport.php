<?php
namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class ProductExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Product::query()
            ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
            ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
            ->leftJoin('part_type', 'part_type.id', '=', 'product.part_type_id')
            ->where('product.is_deleted', 0)
            ->where('product.seller_id', Auth::id());

        if (!empty($this->filters['brand_id'])) {
            $query->where('product.brand_id', $this->filters['brand_id']);
        }
        if (!empty($this->filters['model_id'])) {
            $query->where('product.model_id', $this->filters['model_id']);
        }
        if (!empty($this->filters['category_id'])) {
            $query->where('product.category_id', $this->filters['category_id']);
        }
        if (!empty($this->filters['subcategory_id'])) {
            $query->where('product.subcategory_id', $this->filters['subcategory_id']);
        }
        

        $query->selectRaw('
                product.id,
                brand.brand_name as brand,
                make_model.model_name as model,
                category.category_name as category,
                subcategory.subcat_name as subcategory,
                part_type.part_type_label AS variant,
                product.product_price,
                product.quantity,
                CONCAT(generation_year.start_year, " - ", generation_year.end_year) as generation
            ');
        return $query->get();
    }

    public function headings(): array
    {
        return ['ID', 'Brand', 'Model', 'Part Type', 'Part','Varient', 'Price', 'Quantity', 'Generation'];
    }

    public function styles(Worksheet $sheet)
    {
        // Wrap text for column E (Part column = 5th column)
        $sheet->getStyle('E2:E1000')->getAlignment()->setWrapText(true);
        return [];
    }
}
