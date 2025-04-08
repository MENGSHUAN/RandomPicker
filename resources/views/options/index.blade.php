<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>選項列表</title>
    <link rel="icon" type="image/png" href="{{ asset('icons/pic.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <h1>選項列表</h1>
                <a href="{{ url('/slot') }}" class="btn btn-primary">
                    返回拉霸機
                </a>
            </div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                新增選項
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名稱</th>
                        <th>是否已抽中</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($options as $option)
                    <tr>
                        <td>{{ $option->id }}</td>
                        <td>{{ $option->name }}</td>
                        <td>
                            @if($option->is_drawn)
                                <span class="badge bg-success">已抽中</span>
                            @else
                                <span class="badge bg-secondary">未抽中</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $option->id }}">
                                編輯
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $option->id }}">
                                刪除
                            </button>
                        </td>
                    </tr>

                    <!-- 編輯 Modal -->
                    <div class="modal fade" id="editModal{{ $option->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $option->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $option->id }}">編輯選項</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('options.update', $option->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name{{ $option->id }}" class="form-label">名稱</label>
                                            <input type="text" class="form-control" id="name{{ $option->id }}" name="name" value="{{ $option->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_drawn{{ $option->id }}" name="is_drawn" {{ $option->is_drawn ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_drawn{{ $option->id }}">
                                                    已抽中
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-primary">儲存</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 刪除 Modal -->
                    <div class="modal fade" id="deleteModal{{ $option->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $option->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $option->id }}">確認刪除</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    您確定要刪除「{{ $option->name }}」這個選項嗎？
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <form action="{{ route('options.destroy', $option->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">確認刪除</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 新增選項 Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">新增選項</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('options.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">名稱</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_drawn" name="is_drawn">
                                <label class="form-check-label" for="is_drawn">
                                    已抽中
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-success">新增</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 