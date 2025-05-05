import HeaderProvider from '@/components/contents/HeaderProvider';
import TablePage from '@/components/contents/TablePage';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

export default function TableDemo() {
    return (
        <HeaderProvider>
            <div className="mx-5 my-5">
                <Tabs defaultValue="account" className="w-full">
                    <div className="flex-items-center">
                        <TabsList className="grid grid-cols-5 gap-3 bg-amber-200 lg:w-[550px]">
                            <TabsTrigger value="account">Account</TabsTrigger>
                            <TabsTrigger value="password">Password</TabsTrigger>
                            <TabsTrigger value="faq">FAQ</TabsTrigger>
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
                        <p>Frequently Ask Question</p>
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
    );
}
