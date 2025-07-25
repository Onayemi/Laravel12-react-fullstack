import { Link } from '@inertiajs/react';
import { Button } from '../ui/button';

interface LinksType {
    url: string;
    label: string;
    active: boolean;
}
interface ProductType {
    links: LinksType[];
    to: number;
    from: number;
    total: number;
}
export default function ProductsPagination({ products }: { products: ProductType }) {
    return (
        <div className="mt-4 flex flex-wrap items-center justify-center gap-2 px-10 sm:justify-between">
            <div>
                {products.from} - {products.to} of {products.total}
            </div>
            <div className="flex flex-wrap items-center justify-center gap-2">
                {products.links.map((link, index) => {
                    const isDisabled = !link.url;

                    if (isDisabled) {
                        return (
                            <Button
                                key={index}
                                variant={'outline'}
                                disabled
                                className="pointer-events-none opacity-50"
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        );
                    }
                    return (
                        <Button key={index} asChild variant={link.active ? 'default' : 'outline'}>
                            <Link href={link.url} dangerouslySetInnerHTML={{ __html: link.label }}></Link>
                        </Button>
                    );
                })}
            </div>
        </div>
    );
}
