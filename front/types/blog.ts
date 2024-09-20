  export interface Blog {
    id?: number;
    title: string;
    description: string;
  }
  
  export interface BlogEntry {
    id?: number;
    title: string;
    content: string;
    blog_id: number;
    created_at?: string;
    updated_at?: string;
    comments: Comment[]; 
  }
  
  export interface Comment {
    id?: number;
    content: string;
    entry_id: number;
    user: {
        id: number;
        name: string;
    }; 
    created_at?: string;
    updated_at?: string;
}
  