import HeaderProvider from '@/components/contents/HeaderProvider';
import TablePage from '@/components/contents/TablePage';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Head } from '@inertiajs/react';
import QRCode from 'react-qr-code';

export default function TableDemo() {
    const qrValue = JSON.stringify({
        name: 'John Doe',
        email: 'john@example.com',
        website: 'https://remlextech.com',
    });

    return (
        <>
            <Head>
                <title>Table </title>
                <meta head-key="description" name="description" content="This is the default description" />
                <meta name="keywords" content="HTML, CSS, JavaScript" />
            </Head>
            <HeaderProvider>
                <div className="mx-5 my-5">
                    <Tabs defaultValue="account" className="w-full">
                        <div className="flex-items-center">
                            <TabsList className="grid grid-cols-5 gap-3 bg-gray-400 lg:w-[550px]">
                                <TabsTrigger value="account">Account</TabsTrigger>
                                <TabsTrigger value="password">Password</TabsTrigger>
                                <TabsTrigger value="faq">QrCode</TabsTrigger>
                                <TabsTrigger value="table">Table</TabsTrigger>
                                <TabsTrigger value="banner">Banner</TabsTrigger>
                            </TabsList>
                        </div>
                        <TabsContent value="account">
                            <TablePage />
                        </TabsContent>
                        <TabsContent value="password">
                            <p>Password</p>
                        </TabsContent>
                        <TabsContent value="faq">
                            <p className="mb-10">Frequently Ask Question</p>
                            <QRCode value={qrValue} className="mx-auto my-4" />
                            {/* <QRCode value="https://www.youtube.com/@CodeShotcut" /> */}
                        </TabsContent>
                        <TabsContent value="table">
                            <p>Table</p>
                        </TabsContent>
                        <TabsContent value="banner">
                            <p>Banner</p>
                        </TabsContent>
                    </Tabs>
                </div>
                {/* <TablePage /> */}
            </HeaderProvider>
        </>
    );
}
