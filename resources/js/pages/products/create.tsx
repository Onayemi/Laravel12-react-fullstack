import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Product',
        href: '/create/products',
    },
];

export default function CreateProduct() {
    const { data, setData, reset, processing, post, errors } = useForm<{
        title: string;
        category: string;
        amount: string;
        content: string;
        status: string;
        image: File | null;
    }>({
        title: '',
        category: '',
        amount: '',
        content: '',
        status: '',
        image: null,
    });

    const handleFormSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post('/pay', {
            // route('payment.initialize')
            onSuccess: () => {
                toast.success('Products has been created.');
                reset();
                // window.location.href = response.data.data.authorization_url;
            },
            // onError: () => {
            //     toast.error('Posts has not been created.');
            // },
        });
    };

    // const handlePay = async () => {
    //     try {
    //       const res = await axios.post('/paystack/initialize', { email, amount });
    //       window.location.href = res.data.data.authorization_url;
    //     } catch (err) {
    //       alert("Payment initialization failed.");
    //       console.error(err);
    //     }
    //   };

    // File Handling
    const handleFileUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files.length > 0) {
            setData('image', e.target.files[0]);
        }
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Posts" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="rounded border p-6 shadow-xl">
                    <div className="mb-5 flex items-center justify-between">
                        <div className="text-xl text-slate-600"> Create Product</div>
                        <Button>
                            <Link href="/products" prefetch>
                                Go Back
                            </Link>
                        </Button>
                    </div>
                    <Card>
                        <CardContent>
                            <form onSubmit={handleFormSubmit}>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="col-span-2 md:col-span-1">
                                        <Label htmlFor="title">Title</Label>
                                        <Input
                                            type="text"
                                            id="title"
                                            placeholder="Title"
                                            value={data.title}
                                            onChange={(e) => setData('title', e.target.value)}
                                            aria-invalid={!!errors.title}
                                        />
                                        <InputError message={errors.title} />
                                    </div>
                                    <div className="col-span-2 md:col-span-1">
                                        <Label htmlFor="title">Amount</Label>
                                        <Input
                                            type="number"
                                            id="amount"
                                            placeholder="0.00"
                                            value={data.amount}
                                            onChange={(e) => setData('amount', e.target.value)}
                                            aria-invalid={!!errors.amount}
                                        />
                                        <InputError message={errors.amount} />
                                    </div>
                                    <div className="col-span-2 md:col-span-1">
                                        <Label htmlFor="category">Category</Label>
                                        <Select value={data.category} onValueChange={(e) => setData('category', e)}>
                                            <SelectTrigger id="category" aria-invalid={!!errors.category}>
                                                <SelectValue placeholder="Select Category" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="Marvel">Marvel</SelectItem>
                                                <SelectItem value="DC">DC</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError message={errors.category} />
                                    </div>
                                    <div className="col-span-2 md:col-span-1">
                                        <Label htmlFor="status">Status</Label>
                                        <Select value={data.status} onValueChange={(e) => setData('status', e)}>
                                            <SelectTrigger id="status" aria-invalid={!!errors.status}>
                                                <SelectValue placeholder="Select Status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="1">Active</SelectItem>
                                                <SelectItem value="0">Inactive</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError message={errors.status} />
                                    </div>
                                </div>
                                <div className="mt-4">
                                    <Label htmlFor="content">Content</Label>
                                    <Textarea
                                        rows={6}
                                        id="content"
                                        placeholder="Type your message here."
                                        value={data.content}
                                        onChange={(e) => setData('content', e.target.value)}
                                        aria-invalid={!!errors.content}
                                    />
                                    <InputError message={errors.content} />
                                </div>
                                <div className="mt-4">
                                    <Label htmlFor="image">Select Image</Label>
                                    <Input type="file" id="image" onChange={handleFileUpload} aria-invalid={!!errors.image} />
                                    <InputError message={errors.image} />

                                    {data.image && (
                                        <img src={URL.createObjectURL(data.image)} alt="Preview" className="objet-cover mt-2 w-20 rounded-lg" />
                                    )}
                                </div>
                                <div className="mt-4 text-end">
                                    <Button size={'lg'} type="submit" disabled={processing}>
                                        {processing && <Loader2 className="animate-spin" />}
                                        <span>Create Product</span>
                                    </Button>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
