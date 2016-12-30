import java.io.File;
import java.text.DecimalFormat;
import java.io.FileInputStream;
import java.util.Arrays;
public class Combine{
    public static boolean delAllFile(String path) {
        boolean flag = false;
        File file = new File(path);
        if (!file.exists()) {
            System.out.println("資料夾為空");
            return flag;
        }
        if (!file.isDirectory()) {
            return flag;
        }
        String[] tempList = file.list();
        Arrays.sort(tempList);
        File temp = null;
        for (int i = 0; i < tempList.length/2; i++) {
            if (path.endsWith(File.separator)) {
                temp = new File(path + tempList[i]);
            } else {
                temp = new File(path + File.separator + tempList[i]);
            }
            if (temp.isFile()) {
                temp.delete();
            }
            if (temp.isDirectory()) {
                delAllFile(path + "/" + tempList[i]);//先刪除檔夾裏面的檔
                //delFolder(path + "/" + tempList[i]);//再刪除空檔夾
                flag = true;
            }
        }
            return flag;
    }
    public long getFileSizes(File f) throws Exception{//取得檔案大小
        long s=0;
        if (f.exists()) {
            FileInputStream fis = null;
            fis = new FileInputStream(f);
            s= fis.available();
        } else {
            f.createNewFile();
            System.out.println("檔不存在");
        }
            return s;
    }
        // 遞迴
    public long getFileSize(File f)throws Exception{//取得資料夾大小
        long size = 0;
        File flist[] = f.listFiles();
        for (int i = 0; i < flist.length; i++)
        {
            if (flist[i].isDirectory()){
                size = size + getFileSize(flist[i]);
            } else{
                size = size + flist[i].length();
            }
        }
        return size;
    }
    public String FormetFileSize(long fileS) {//轉換檔案大小
        DecimalFormat df = new DecimalFormat("#.00");
        String fileSizeString = "";
        float sizes;
        if (fileS < 1024) {
            fileSizeString = df.format((double) fileS) + "B";
        } else if (fileS < 1048576) {
            fileSizeString = df.format((double) fileS / 1024) + "K";
        } else if (fileS < 1073741824) {
            fileSizeString = df.format((double) fileS / 1048576) + "M";
            /*sizes = Float.parseFloat(df.format((double) fileS / 1048576));
            if(sizes >= 30.0){
                delAllFile("/home/pi/cam");*/
            }
        } else {
            fileSizeString = df.format((double) fileS / 1073741824) + "G";
            sizes = Float.parseFloat(df.format((double) fileS / 1073741824));
            if(sizes >= 5.0){
                delAllFile("/home/pi/camera");
            }
        }
        return fileSizeString;
    }
    public long getlist(File f){//遞迴求取目錄檔個數
        long size = 0;
        File flist[] = f.listFiles();
        size=flist.length;
        for (int i = 0; i < flist.length; i++) {
            if (flist[i].isDirectory()) {
                size = size + getlist(flist[i]);
                size--;
            }
        }
        return size;
    }
    public static void main(String args[]){
        Combine g = new Combine();
		//GetFileSize g = new GetFileSize();
        long startTime = System.currentTimeMillis();
        String path = "/home/pi/cam";
        while(true){
            try{
                long l = 0;
                String a = "";
                File ff = new File(path);
                if (ff.isDirectory()) { //如果路徑是資料夾的時候
                    //System.out.println("檔個數 " + g.getlist(ff));
                    //System.out.println("目錄");
                    l = g.getFileSize(ff);
                    a = g.FormetFileSize(l);
                    //System.out.println(path + "目錄的大小為：" + g.FormetFileSize(l));
                } else {
                    //System.out.println(" 檔個數 1");
                    //System.out.println("檔");
                    l = g.getFileSizes(ff);
                    a = g.FormetFileSize(l);
                    //System.out.println(path + "檔的大小為：" + g.FormetFileSize(l));
                }
            } catch (Exception e){
                e.printStackTrace();
            }
        }
        //long endTime = System.currentTimeMillis();
        //System.out.println("總共花費時間為：" + (endTime - startTime) + "毫秒...");
    }
}